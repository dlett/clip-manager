<?php

namespace App\Jobs;

use App\Models\Broadcaster;
use App\Services\ClipImportService;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveClip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const MAX_RETRY_ATTEMPTS = 10;

    public $broadcaster;

    public $clip;

    private $retries;

    public function __construct(Broadcaster $broadcaster, array $clip, int $retries = 0)
    {
        $this->broadcaster = $broadcaster;
        $this->clip = $clip;
        $this->retries = $retries;
    }

    public function handle(ClipImportService $clipImportService)
    {
        try {
            $clipImportService->saveClip($this->broadcaster, $this->clip);
        } catch (ClientException $clientException) {
            $tooManyRequests = $clientException->getResponse()->getStatusCode() === Response::HTTP_TOO_MANY_REQUESTS;
            if ($tooManyRequests && $this->retries < self::MAX_RETRY_ATTEMPTS) {
                logger()->info('Twitch:RateLimited:SaveClip', [
                    'broadcaster_id' => $this->broadcaster->id,
                    'retries' => $this->retries
                ]);
                dispatch(new self($this->broadcaster, $this->clip, ++$this->retries))
                    ->delay(now()->addMinutes(2));
            }
            throw $clientException;
        }
    }
}

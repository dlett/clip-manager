<?php

namespace App\Jobs;

use App\Models\Broadcaster;
use App\Services\ClipImportService;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetClipsPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const MAX_RETRY_ATTEMPTS = 10;

    public $broadcaster;

    public $startAt;

    public $endAt;

    public $cursor;

    private $retries;

    public function __construct(Broadcaster $broadcaster, Carbon $startAt, Carbon $endAt, string $cursor = null, int $retries = 0)
    {
        $this->broadcaster = $broadcaster;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->cursor = $cursor;
        $this->retries = $retries;
    }

    public function handle(ClipImportService $clipImportService)
    {
        try {
            $clipImportService->getClipsPage($this->broadcaster, $this->startAt, $this->endAt, $this->cursor);
        } catch (ClientException $clientException) {
            $tooManyRequests = $clientException->getResponse()->getStatusCode() === Response::HTTP_TOO_MANY_REQUESTS;
            if ($tooManyRequests && $this->retries < self::MAX_RETRY_ATTEMPTS) {
                logger()->info('Twitch:RateLimited:GetClipsPage', [
                    'broadcaster_id' => $this->broadcaster->id,
                    'start_at' => (string)$this->startAt,
                    'end_at' => (string)$this->endAt,
                    'cursor' => $this->cursor,
                    'retries' => $this->retries
                ]);
                dispatch(new self($this->broadcaster, $this->startAt, $this->endAt, $this->cursor, ++$this->retries))
                    ->delay(now()->addMinutes(2));
            }
            throw $clientException;
        }
    }
}

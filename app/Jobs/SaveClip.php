<?php

namespace App\Jobs;

use App\Models\Broadcaster;
use App\Services\ClipImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveClip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $broadcaster;

    private $clip;

    public function __construct(Broadcaster $broadcaster, array $clip)
    {
        $this->broadcaster = $broadcaster;
        $this->clip = $clip;
    }

    public function handle(ClipImportService $clipImportService)
    {
        $clipImportService->saveClip($this->broadcaster, $this->clip);
    }
}

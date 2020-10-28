<?php

namespace App\Jobs;

use App\Models\Broadcaster;
use App\Services\ClipImportService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetClipsPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $broadcaster;

    private $startAt;

    private $endAt;

    private $cursor;

    public function __construct(Broadcaster $broadcaster, Carbon $startAt, Carbon $endAt, $cursor = null)
    {
        $this->broadcaster = $broadcaster;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->cursor = $cursor;
    }

    public function handle(ClipImportService $clipImportService)
    {
        $clipImportService->getClipsPage($this->broadcaster, $this->startAt, $this->endAt, $this->cursor);
    }
}

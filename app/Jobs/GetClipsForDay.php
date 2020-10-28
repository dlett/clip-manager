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

class GetClipsForDay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $broadcaster;

    public $date;

    public function __construct(Broadcaster $broadcaster, Carbon $date)
    {
        $this->broadcaster = $broadcaster;
        $this->date = $date;
    }

    public function handle(ClipImportService $clipImportService)
    {
        $clipImportService->getClipsForDay($this->broadcaster, $this->date);
    }
}

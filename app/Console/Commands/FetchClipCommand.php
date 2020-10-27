<?php

namespace App\Console\Commands;

use App\Repositories\ClipRepository;
use Illuminate\Console\Command;

class FetchClipCommand extends Command
{
    protected $signature = 'fetch-clip';

    public function handle(ClipRepository $clipRepository)
    {
        $arr = $clipRepository->getClip('CleanbitterOryxPogChamp');

        dd($arr);
    }
}

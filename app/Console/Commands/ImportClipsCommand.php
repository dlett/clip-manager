<?php

namespace App\Console\Commands;

use App\Services\ClipImportService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportClipsCommand extends Command
{
    protected $signature = 'import-clips {disk : The disk to pull files from} {directory : The directory to look for videos}';

    public function handle(ClipImportService $clipImportService)
    {
        $files = Storage::disk($this->argument('disk'))->allFiles($this->argument('directory'));
        foreach ($files as $file) {
            preg_match('/.*-(.*).mp4/', $file, $matches);
            if (count($matches) > 2) {
                dd($matches);
            }

            $slug = $matches[1];

            $clipImportService->importClip($slug, $file);
        }
    }
}

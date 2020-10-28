<?php

namespace App\Console\Commands;

use App\Models\Broadcaster;
use App\Repositories\UserRepository;
use App\Services\ClipImportService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportBroadcasterCommand extends Command
{
    protected $signature = 'import-broadcaster {broadcaster : The name of the broadcaster} {first : The date of the first twitch clip}';

    public function handle(UserRepository $userRepository, ClipImportService $clipImportService)
    {
        $user = $userRepository->getUser($this->argument('broadcaster'));

        /** @var Broadcaster $broadcaster */
        $broadcaster = Broadcaster::query()->firstOrCreate([
            'twitch_id' => $user['id'],
        ], [
            'name' => $user['login'],
            'display_name' => $user['display_name'],
            'logo_url' => $user['profile_image_url'],
        ]);

        $clipImportService->importClipsForDateRange($broadcaster, Carbon::parse($this->argument('first')), Carbon::now());
    }
}

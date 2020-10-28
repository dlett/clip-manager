<?php

namespace App\Services;

use App\Models\Broadcaster;
use App\Models\Clip;
use App\Models\Curator;
use App\Repositories\ClipRepository;
use Illuminate\Support\Facades\Storage;

class ClipImportService
{
    const THUMBNAILS_DIRECTORY = 'thumbnails/';

    private $clipRepository;

    public function __construct(ClipRepository $clipRepository)
    {
        $this->clipRepository = $clipRepository;
    }

    public function importClip(string $slug, string $videoFilePath)
    {
        $clipData = $this->clipRepository->getClip($slug);

        $thumbnailMedium = $this->moveThumbnailToStorage($clipData['thumbnails']['medium']);
        $thumbnailSmall = $this->moveThumbnailToStorage($clipData['thumbnails']['small']);
        $thumbnailTiny = $this->moveThumbnailToStorage($clipData['thumbnails']['tiny']);

        $curator = $this->findOrCreateCurator($clipData['curator']);
        $broadcaster = $this->findOrCreateBroadcaster($clipData['broadcaster']);

        $clip = new Clip([
            'slug' => $clipData['slug'],
            'game' => $clipData['game'],
            'broadcast_id' => $clipData['broadcast_id'],
            'title' => $clipData['title'],
            'views_at_import' => $clipData['views'],
            'duration' => $clipData['duration'],
            'thumbnail_medium' => $thumbnailMedium,
            'thumbnail_small' => $thumbnailSmall,
            'thumbnail_tiny' => $thumbnailTiny,
            'created_at' => $clipData['created_at'],
            'video_file_path' => Storage::disk('s3')->url($videoFilePath),
        ]);
        $clip->curator()->associate($curator);
        $clip->broadcaster()->associate($broadcaster);
        $clip->save();
    }

    protected function findOrCreateCurator(array $curator): Curator
    {
        return Curator::query()->firstOrCreate([
            'name' => $curator['name'],
            'display_name' => $curator['display_name'],
        ], [
            'channel_url' => $curator['channel_url'],
            'logo_url' => $curator['logo'],
        ]);
    }

    protected function findOrCreateBroadcaster(array $broadcaster): Broadcaster
    {
        return Broadcaster::query()->firstOrCreate([
            'name' => $broadcaster['name'],
            'display_name' => $broadcaster['display_name'],
        ], [
            'channel_url' => $broadcaster['channel_url'],
            'logo_url' => $broadcaster['logo'],
        ]);
    }

    protected function moveThumbnailToStorage(string $url): string
    {
        $contents = file_get_contents($url);
        $name = substr($url, strrpos($url, '/') + 1);
        $path = sprintf('%s%s', self::THUMBNAILS_DIRECTORY, $name);

        Storage::disk('s3')->put($path, $contents, 'public');

        return Storage::disk('s3')->url($path);
    }
}

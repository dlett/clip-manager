<?php

namespace App\Services;

use App\Jobs\GetClipsForDay;
use App\Jobs\GetClipsPage;
use App\Jobs\SaveClip;
use App\Models\Broadcaster;
use App\Models\Clip;
use App\Models\Curator;
use App\Repositories\ClipRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

class ClipImportService
{
    const THUMBNAILS_DIRECTORY = 'thumbnails/';

    const VIDEOS_DIRECTORY = 'videos/';

    const CLIP_URL_FORMAT = 'https://clips-media-assets2.twitch.tv/%s.mp4';

    private $clipRepository;

    public function __construct(ClipRepository $clipRepository)
    {
        $this->clipRepository = $clipRepository;
    }

    public function importClipsForDateRange(Broadcaster $broadcaster, Carbon $start, Carbon $end)
    {
        $date = clone $start;
        while ($date->lt($end)) {
            dispatch(new GetClipsForDay($broadcaster, $date));
            $date->addDay();
        }
    }

    public function getClipsForDay(Broadcaster $broadcaster, Carbon $day)
    {
        $end = clone $day;
        $end->addDay();

        dispatch(new GetClipsPage($broadcaster, $day, $end));
    }

    public function getClipsPage(Broadcaster $broadcaster, Carbon $startAt, Carbon $endAt, $cursor = null)
    {
        $page = $this->clipRepository->getClips($broadcaster->twitch_id, $startAt, $endAt, $cursor);
        $cursor = $page['pagination']['cursor'] ?? null;
        if (is_string($cursor) && strlen($cursor) > 0) {
            dispatch(new GetClipsPage($broadcaster, $startAt, $endAt));
        }

        foreach ($page['data'] ?? [] as $clip) {
            dispatch(new SaveClip($broadcaster, $clip));
        }
    }

    public function saveClip(Broadcaster $broadcaster, array $clip)
    {
        $videoPath = $this->moveVideoToStorage($this->getClipUrlFromThumbnail($clip['thumbnail_url']));

        $this->importClip($clip['slug'], $videoPath);
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
            'twitch_id' => $broadcaster['id'],
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

    protected function moveVideoToStorage(string $url): string
    {
        $contents = file_get_contents($url);
        $name = substr($url, strrpos($url, '/') + 1);
        $path = sprintf('%s%s', self::VIDEOS_DIRECTORY, $name);

        Storage::disk('s3')->put($path, $contents, 'public');

        return Storage::disk('s3')->url($path);
    }

    protected function getClipUrlFromThumbnail($thumbnailUrl): string
    {
        preg_match('/https:\/\/clips\-media\-assets2\.twitch\.tv\/(.*)\-preview\-480x272\.jpg/', $thumbnailUrl, $matches);

        if (count($matches) !== 2) {
            throw new Exception('Invalid thumbnail URL for transformation: ' . $thumbnailUrl);
        }

        return sprintf(self::CLIP_URL_FORMAT, $matches[1]);
    }
}

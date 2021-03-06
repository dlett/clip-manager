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
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Response;
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
            if (Clip::query()->where('slug', $clip['id'])->exists()) {
                continue;
            }

            dispatch(new SaveClip($broadcaster, $clip));
        }
    }

    public function saveClip(Broadcaster $broadcaster, array $clip)
    {
        $videoPath = $this->moveVideoToStorage($this->getClipUrlFromThumbnail($clip['thumbnail_url']));

        $this->importClip($clip['id'], $videoPath);
    }

    public function importClip(string $slug, string $videoFilePath)
    {
        if (Clip::query()->where('slug', $slug)->exists()) {
            return;
        }

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
            'video_file_disk' => 's3',
            'video_file_path' => $videoFilePath,
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

    protected function findOrCreateBroadcaster(array $data): Broadcaster
    {
        $broadcaster = Broadcaster::query()->firstOrCreate([
            'twitch_id' => $data['id'],
        ], [
            'name' => $data['name'],
            'display_name' => $data['display_name'],
            'channel_url' => $data['channel_url'],
            'logo_url' => $data['logo'],
        ]);

        if (is_null($broadcaster->channel_url) && !is_null($data['channel_url'])) {
            $broadcaster->update(['channel_url' => $data['channel_url']]);
        }

        return $broadcaster;
    }

    public function moveThumbnailToStorage(string $url): ?string
    {
        try {
            $response = (new Client([
                'headers' => ['Connection' => 'close'],
                CURLOPT_FORBID_REUSE => true,
                CURLOPT_FRESH_CONNECT => true
            ]))->get($url);

            $name = substr($url, strrpos($url, '/') + 1);
            $path = sprintf('%s%s', self::THUMBNAILS_DIRECTORY, $name);
            $contents = $response->getBody()->getContents();

            Storage::disk('s3')->put($path, $contents, 'public');

            return Storage::disk('s3')->url($path);
        } catch (ClientException $clientException) {
            // Forbidden in this case usually means the thumbnail just does not exist.
            if ($clientException->getResponse()->getStatusCode() === Response::HTTP_FORBIDDEN) {
                return null;
            }
            throw $clientException;
        }
    }

    protected function moveVideoToStorage(string $url): string
    {
        $contents = file_get_contents($url);
        $name = substr($url, strrpos($url, '/') + 1);
        $path = sprintf('%s%s', self::VIDEOS_DIRECTORY, $name);

        Storage::disk('s3')->put($path, $contents, 'public');

        return $path;
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

<?php

namespace App\Repositories;

use App\Jobs\RefreshToken;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Response;

class ClipRepository extends AbstractTwitchRepository
{
    const CLIP_FETCH_URI = 'kraken/clips/%s';

    const CLIP_INDEX_URI = 'helix/clips';

    public function getClip(string $slug): array
    {
        $response = $this->client()->get(sprintf(self::CLIP_FETCH_URI, $slug));

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getClips($broadcasterId, Carbon $startAt, Carbon $endAt, $cursor = null)
    {
        try {
            $query = [
                'broadcaster_id' => $broadcasterId,
                'first'          => 100,
                'started_at'     => $startAt->toRfc3339String(),
                'ended_at'       => $endAt->toRfc3339String(),
            ];

            if ($cursor) {
                $query['after'] = $cursor;
            }

            $response = $this->client()->get(self::CLIP_INDEX_URI, [
                'query'   => $query,
                'headers' => [
                    'Authorization' => 'Bearer ' . User::first()->token,
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $exception) {
            if ($exception->getResponse()->getStatusCode() === Response::HTTP_UNAUTHORIZED) {
                dispatch(new RefreshToken(User::first()));
                $this->getClips($broadcasterId, $startAt, $endAt, $cursor);
            }
            throw $exception;
        }
    }
}

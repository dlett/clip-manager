<?php

namespace App\Repositories;

class ClipRepository extends AbstractTwitchRepository
{
    const CLIP_FETCH_URI = 'clips/%s';

    public function getClip(string $slug): array
    {
        $response = $this->client()->get(sprintf(self::CLIP_FETCH_URI, $slug));

        return json_decode($response->getBody()->getContents(), true);
    }
}

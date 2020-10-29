<?php

namespace App\Repositories;

use GuzzleHttp\Client;

class TokenRepository extends AbstractTwitchRepository
{
    public function refreshToken(string $refreshToken): array
    {
        $client = new Client();

        $response = $client->post('https://id.twitch.tv/oauth2/token', [
            'json' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => config('services.twitch.client_id'),
                'client_secret' => config('services.twitch.client_secret')
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}

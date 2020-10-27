<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use Illuminate\Contracts\Config\Repository as Config;

abstract class AbstractTwitchRepository
{
    private $clientId;

    public function __construct(Config $config)
    {
        $this->clientId = $config->get('services.twitch.client_id');
    }

    protected function client()
    {
        return new Client([
            'base_uri' => 'https://api.twitch.tv/kraken/',
            'headers' => [
                'Accept' => 'application/vnd.twitchtv.v5+json',
                'Client-ID' => $this->clientId
            ]
        ]);
    }
}

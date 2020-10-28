<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Arr;

class UserRepository extends AbstractTwitchRepository
{
    const USER_INDEX_URI = 'helix/users';

    public function getUser(string $name)
    {
        $response = $this->client()->get(self::USER_INDEX_URI, [
            'query' => [
                'login' => $name
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . User::first()->token, // todo
            ]
        ]);

        $contents = json_decode($response->getBody()->getContents(), true);

        return Arr::get($contents, 'data.0');
    }
}

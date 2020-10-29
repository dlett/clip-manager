<?php

namespace App\Jobs;

use App\Models\User;
use App\Repositories\TokenRepository;

class RefreshToken
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(TokenRepository $tokenRepository)
    {
        $newToken = $tokenRepository->refreshToken($this->user->refresh_token);

        $this->user->update([
            'access_token' => $newToken['access_token'],
            'refresh_token' => $newToken['refresh_token'],
        ]);
    }
}

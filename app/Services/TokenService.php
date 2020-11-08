<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\TokenRepository;

class TokenService
{
    private $tokenRepository;

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function refreshToken(User $user)
    {
        $newToken = $this->tokenRepository->refreshToken($user->refresh_token);

        $user->update([
            'token' => $newToken['access_token'],
            'refresh_token' => $newToken['refresh_token'],
        ]);
    }
}

<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\TokenService;

class RefreshToken
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(TokenService $tokenService)
    {
        $tokenService->refreshToken($this->user);
    }
}

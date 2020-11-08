<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Laravel\Socialite\Contracts\Factory;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends Controller
{
    private $socialiteFactory;

    private $authManager;

    public function __construct(Factory $socialiteFactory, AuthManager $authManager)
    {
        $this->socialiteFactory = $socialiteFactory;
        $this->authManager = $authManager;
    }

    public function redirectToProvider(): RedirectResponse
    {
        return $this->socialiteFactory->driver('twitch')->scopes(['moderation:read'])->redirect();
    }

    public function handleProviderCallback()
    {
        /** @var \Laravel\Socialite\Two\User $twitchUser */
        $twitchUser = $this->socialiteFactory->driver('twitch')->user();

        /** @var User $user */
        $user = User::query()->updateOrCreate([
            'email' => $twitchUser->getEmail()
        ], [
            'name'          => $twitchUser->getName(),
            'nickname'      => $twitchUser->getNickname(),
            'email'         => $twitchUser->getEmail(),
            'token'         => $twitchUser->token,
            'refresh_token' => $twitchUser->refreshToken,
            'avatar_url'    => $twitchUser->getAvatar(),
        ]);

        $this->authManager->guard()->login($user, true);

        return redirect()->route('home');
    }
}

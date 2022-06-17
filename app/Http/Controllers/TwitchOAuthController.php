<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwitchOAuthController extends Controller
{
    /**
     * Provide a canonical URL to retrieve Twitch OAuth token
     * 
     * @var Response
     */

    public function redirect() {
        return Socialite::driver('twitch')
            ->scopes(['moderation:read'])
            ->redirect();
    }

    /**
     * Update or create new user and login as that user
     * 
     * @var Response
     */

    public function authorized() {
        $twitchUser = Socialite::driver('twitch')->user();

        $user = User::where('provider_id', $twitchUser->id)->first();

        $token = $twitchUser->token;
        $refreshToken = $twitchUser->refreshToken;

        if ($user == null) {
            $user = new User();
            $user->name = $twitchUser->name;
            $user->provider_id = $twitchUser->id;
            $user->email = $twitchUser->email;
        }

        $user->provider_token = $token;
        $user->refresh_token = $refreshToken;
        $user->save();

        Auth::login($user);

        return response()->redirectToRoute('dashboard');
    }
}

<?php

namespace App\Http\Controllers;

use Ahc\Jwt\JWT;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;
use Laravel\Sanctum\PersonalAccessToken;

class PersonalWebTokenController extends Controller
{
    public function requestToken(Request $request)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token');

        return ['token' => $token->plainTextToken];
    }

    /**
     * Create a person web token
     *
     * @param  string provider_id
     * @return \Illuminate\Http\Response
     */
    public function requestTokenFromTwitchJWT(Request $request)
    {
        $secret = base64_decode(env('TWITCH_CLIENT_SECRET'));
        $jwt = new JWT($secret, 'HS256');
        $payload = [];
        try {
            $payload = $jwt->decode($request->bearerToken());
        } catch (Exception $e)
        {
            Log::error($e->getMessage());
            abort(403);
        }

        $user = User::where('provider_id', $payload['user_id'])->first();
        if ($user == null)
        {
            $user = new User();
            $user->provider_id = $payload['user_id'];
            $user->name = $payload['opaque_user_id'];
            $user->save();
        }

        $user->tokens()->delete();

        $personalAccessToken = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json([
                'token' => $personalAccessToken,
                'user_id' => $payload['user_id']
            ]);
    }
}

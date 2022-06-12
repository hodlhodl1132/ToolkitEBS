<?php

namespace App\Library\Services;
use Ahc\Jwt\JWT;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class TwitchApi {

    /**
     * Provides a signed JWT for API Authentication
     * 
     * @param \App\Models\User $user
     * @return string
     */
    private static function signedToken(User $user, array $args)
    {
        $secret = base64_decode( env( 'TWITCH_CLIENT_SECRET' ) );
        $jwt = new JWT($secret, 'HS256', 180, 10);

        $defaults = [
            'exp' => Carbon::now()->addMinutes(3)->timestamp,
            'user_id' => $user->provider_id,
            'role' => 'external'
        ];

        $token = $jwt->encode(array_merge($defaults, $args));

        return $token;
    }

    /**
     * Send a message through a broadcasters pubsub
     * 
     * @param \App\Models\User $user
     * @param string $data
     */
    public static function sendExtensionPubSubMessage(User $user, string $data)
    {
        $args = [
            'channel_id' => $user->provider_id,
            'pubsub_perms' => [
                'send' => [
                    'broadcast'
                ]
            ]
        ];

        $token = TwitchApi::signedToken($user, $args);
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Client-Id' => env( 'TWITCH_CLIENT_ID' ),
            'Content-Type' => 'application/json'
        ];
        $body = [
            'message' => $data,
            'broadcaster_id' => $user->provider_id,
            'target' => ['broadcast']
        ];

        $statusCode = 500;

        try {
            $guzzleClient = new Client();
            $response = $guzzleClient->post(
            'https://api.twitch.tv/helix/extensions/pubsub',
            ['headers' => $headers, 'body' => json_encode($body) ]);
            $statusCode = $response->getStatusCode();
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
        }

        return $statusCode;
    }
}
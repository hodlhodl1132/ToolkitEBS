<?php

namespace App\Library\Services;
use Ahc\Jwt\JWT;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

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

    private static function refreshUserProviderToken(User $user)
    {       
        try {
            $guzzleClient = new Client();
            $response = $guzzleClient->request(
                'POST',
                'https://id.twitch.tv/oauth2/token', [
                    'form_params' => [
                        'client_id' => env( 'TWITCH_CLIENT_ID' ),
                        'client_secret' => env( 'TWITCH_OAUTH_SECRET' ),
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $user->refresh_token
                    ]
                ]
            );
            
            $body = $response->getBody();
            $body = json_decode($body, true);

            $user->provider_token = $body['access_token'];
            $user->refresh_token = $body['refresh_token'];
            $user->save();
            
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
        }
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

    private static function sendGetRequest(string $endpoint, User $user, int $attempts = 0) : ?ResponseInterface 
    {
        $headers = [
            'Authorization' => 'Bearer ' . $user->provider_token,
            'Client-Id' => env('TWITCH_CLIENT_ID'),
            'Content-Type' => 'application/json'
        ];

        $response = null;

        try {
            $guzzleClient = new Client();
            $response = $guzzleClient->get(
                env( 'TWITCH_HELIX_URI' ) . $endpoint,
                [
                    'headers' => $headers
                ]
            );
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
            if ($attempts == 0)
            {
                TwitchApi::refreshUserProviderToken($user);
                return TwitchApi::sendGetRequest($endpoint, $user, 1);
            }
        }

        return $response;
    }

    /**
     * Get all moderators for a provider id
     * 
     * @param string $providerId
     * @return array $data
     */
    public static function getModerators(string $providerId)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();

        if ($user == null)
            return response('', 500);

        if ($user->provider_id != $providerId && !$user->hasPermissionTo('settings.edit.'.$providerId))
            return response('', 403);

        $broadcasterUser = User::where('provider_id', $providerId)->first();

        if ($broadcasterUser == null)
            return response('', 500);

        $response = TwitchApi::sendGetRequest(
                'moderation/moderators?broadcaster_id='.$providerId,
                $broadcasterUser
        );

        if ($response->getBody() != null)
        {
            $moderators = [];
            $body = $response->getBody();
            $body = json_decode($body, true);
            foreach ($body['data'] as $userData) {
                $user = User::where('provider_id', $userData['user_id'])->first();
                if ($user == null) {
                    $user = new User();
                    $user->provider_id = $userData['user_id'];
                    $user->name = $userData['user_name'];
                    $user->save();
                }
                array_push($moderators, [
                    'user_name' => $user->name,
                    'provider_id' => $user->provider_id
                ]);
            }

            return $moderators;
        }

        return [];
    }
}
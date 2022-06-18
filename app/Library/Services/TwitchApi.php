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

    private static function sendRequest(string $endpoint, string $bearerToken) : ?ResponseInterface 
    {
        $headers = [
            'Authorization' => 'Bearer ' . $bearerToken,
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
        }

        switch ($response->getStatusCode()) {
            case 200:
                return $response;

            case 401:

                break;
            
            default:
                Log::debug('Unimplemented Status Code: ' . $response->getStatusCode());
                return null;
        }
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

        if ($user->provider_id != $providerId && $user->hasPermissionTo('settings.edit.'.$providerId))
            return response('', 403);

        $broadcasterUser = User::where('provider_id', $providerId)->first();

        if ($broadcasterUser == null)
            return response('', 500);

        $bearerToken = $broadcasterUser->provider_token;

        $response = TwitchApi::sendRequest(
                'moderation/moderators?broadcaster_id='.$providerId,
                $bearerToken
        );

        if ($response->getBody() != null)
        {
            $moderators = [];
            Log::debug($response->getBody());
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
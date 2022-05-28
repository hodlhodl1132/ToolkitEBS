<?php

namespace App\Providers;

use Ahc\Jwt\JWT;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\VerifyTwitchJWT;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class JWTFactoryProvider extends ServiceProvider
{
    /**
     * @var JWT
     */
    private $jwt;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $secret = base64_decode( env('TWITCH_CLIENT_SECRET') );
        $this->jwt = new JWT($secret, 'HS256');
    }

    /**
     * Decode Jwt Token
     * @param string $token
     * @return array
     */
    public function decode(string $token)
    {
        return $this->jwt->decode($token);
    }
}

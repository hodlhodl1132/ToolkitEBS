<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\VerifyTwitchJWT;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class JWTFactoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $config = Configuration::forSymmetricSigner(
        //     new Sha256(),
        //     InMemory::plainText(env('TWITCH_CLIENT_SECRET'))
        // );

        // //
        // $this->app->singleton(VerifyTwichJWT::class, function ($app, $config) {
        //     return new VerifyTwichJWT($config);
        // });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

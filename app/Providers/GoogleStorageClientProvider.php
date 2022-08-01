<?php

namespace App\Providers;

use App\Library\Services\GoogleStorage;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\ServiceProvider;

class GoogleStorageClientProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Library\Services\GoogleStorage', function ($app) {
            return new GoogleStorage();
        });
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

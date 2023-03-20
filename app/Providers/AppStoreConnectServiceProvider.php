<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\AppStoreConnectService;

class AppStoreConnectServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton(AppStoreConnectService::class, function($app) {
            return new AppStoreConnectService();
        });
    }

    public function boot() : void
    {

    }
}

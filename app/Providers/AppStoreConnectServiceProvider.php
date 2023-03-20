<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

use App\Services\AppStoreConnectService;

class AppStoreConnectServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton(AppStoreConnectService::class, function(Application $app) {
            return new AppStoreConnectService();
        });
    }

    public function boot() : void
    {

    }
}

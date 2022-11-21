<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\AppStoreConnectService;

class AppStoreConnectServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->app->bind(AppStoreConnectService::class, function($app) {
            return new AppStoreConnectService();
        });
    }
}

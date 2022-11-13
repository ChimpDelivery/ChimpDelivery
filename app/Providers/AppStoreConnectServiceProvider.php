<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

use App\Services\AppStoreConnectService;

class AppStoreConnectServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->bind(AppStoreConnectService::class, function($app) {
            return new AppStoreConnectService();
        });
    }

    public function provides()
    {
        return [AppStoreConnectService::class];
    }
}

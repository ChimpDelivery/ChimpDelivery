<?php

namespace App\Providers;

use App\Models\User;
use App\Models\AppInfo;

use App\Observers\UserObserver;
use App\Observers\AppInfoObserver;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local'))
        {
            $this->app->register('\Barryvdh\Debugbar\ServiceProvider::class');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // to fix: "huge paginator icons"
        Paginator::useBootstrap();

        //
        User::observe(UserObserver::class);
        AppInfo::observe(AppInfoObserver::class);
    }
}

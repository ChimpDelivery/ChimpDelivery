<?php

namespace App\Providers;

use App\Models\AppInfo;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // to fix: "paginator huge icons"
        Paginator::useBootstrap();

        // to fix: "/SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry..."
        // https://dev.to/mmollick/using-unique-columns-and-soft-deletes-in-laravel-470p
        AppInfo::observe(AppInfoObserver::class);
    }
}

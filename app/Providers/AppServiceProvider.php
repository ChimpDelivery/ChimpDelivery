<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->environment([ 'local', 'staging' ])) {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }
    }

    public function boot()
    {
        // to fix: "huge paginator icons"
        Paginator::useBootstrap();

        Model::shouldBeStrict(!$this->app->isProduction());
    }
}

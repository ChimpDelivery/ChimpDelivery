<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        Paginator::useBootstrap();

        Model::shouldBeStrict(!$this->app->isProduction());

        Password::defaults(function () {
            return Password::min(8)->mixedCase()->uncompromised()->symbols();
        });
    }
}

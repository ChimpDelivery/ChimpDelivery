<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
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
        Password::defaults(fn() => Password::min(8)->mixedCase()->uncompromised()->symbols());

        Paginator::useBootstrap();

        Model::shouldBeStrict(App::isLocal());
    }
}

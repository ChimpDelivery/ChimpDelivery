<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Pennant\Feature;

class AppServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        if ($this->app->environment([ 'local', 'staging' ])) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    public function boot() : void
    {
        if (!App::isLocal())
        {
            Password::defaults(fn() => Password::min(10)
                ->letters()
                ->mixedCase()
                ->uncompromised()
                ->symbols()
                ->numbers()
            );
        }

        Paginator::useBootstrap();

        Model::shouldBeStrict(App::isLocal());

        Feature::discover();
    }
}

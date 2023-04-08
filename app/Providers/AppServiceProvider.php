<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

use Laravel\Pennant\Feature;

use Monicahq\Cloudflare\LaravelCloudflare;
use Monicahq\Cloudflare\Facades\CloudflareProxies;

class AppServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->RegisterDebugServices();
    }

    public function boot() : void
    {
        if (!App::isLocal())
        {
            Password::defaults(
                fn () => Password::min(10)
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

        LaravelCloudflare::getProxiesUsing(fn () => CloudflareProxies::load());
    }

    private function RegisterDebugServices() : void
    {
        if ($this->app->environment([ 'local' ]))
        {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        if ($this->app->hasDebugModeEnabled() && $this->app->environment([ 'local', 'staging' ]))
        {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}

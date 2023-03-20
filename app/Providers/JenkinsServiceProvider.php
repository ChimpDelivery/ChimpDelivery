<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

use App\Services\JenkinsService;

class JenkinsServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton(JenkinsService::class, function(Application $app) {
            return new JenkinsService(
                config('jenkins.host'),
                config('jenkins.user'),
                config('jenkins.token')
            );
        });
    }

    public function boot() : void
    {

    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\JenkinsService;

class JenkinsServiceProvider extends ServiceProvider
{
    public function register() : void
    {

    }

    public function boot() : void
    {
        $this->app->singleton(JenkinsService::class, function($app) {
            return new JenkinsService(
                config('jenkins.host'),
                config('jenkins.user'),
                config('jenkins.token')
            );
        });
    }
}

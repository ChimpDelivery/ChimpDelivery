<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\JenkinsService;

class JenkinsServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
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

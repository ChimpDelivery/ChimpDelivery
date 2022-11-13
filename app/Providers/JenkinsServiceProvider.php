<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

use App\Services\JenkinsService;

class JenkinsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->bind(JenkinsService::class, function($app) {
            return new JenkinsService(
                config('jenkins.host'),
                config('jenkins.user'),
                config('jenkins.token')
            );
        });
    }

    public function provides()
    {
        return [JenkinsService::class];
    }
}

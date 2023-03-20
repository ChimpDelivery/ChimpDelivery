<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Contracts\Foundation\Application;

use App\Services\JenkinsService;

class JenkinsServiceProvider extends ServiceProvider implements DeferrableProvider
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

    public function provides() : array
    {
        return [JenkinsService::class];
    }
}

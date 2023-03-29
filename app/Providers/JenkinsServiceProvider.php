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
        // parameters used in jobs...
        $this->app->singleton(JenkinsService::class, function (Application $app, array $parameters) {
            return new JenkinsService(
                config('jenkins.host'),
                config('jenkins.user'),
                config('jenkins.token'),
                $parameters['user'] ?? null,
            );
        });
    }

    public function provides() : array
    {
        return [JenkinsService::class];
    }
}

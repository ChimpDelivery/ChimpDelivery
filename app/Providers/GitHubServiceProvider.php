<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Contracts\Foundation\Application;

use App\Services\GitHubService;

class GitHubServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register() : void
    {
        $this->app->singleton(GitHubService::class, function(Application $app) {
            return new GitHubService();
        });
    }

    public function provides() : array
    {
        return [GitHubService::class];
    }
}

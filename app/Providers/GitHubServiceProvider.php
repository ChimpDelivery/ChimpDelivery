<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

use App\Services\GitHubService;

class GitHubServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        // Note: GitHub Service must be resolved before using GitHub api!
        $this->app->bind(GitHubService::class, function($app) {
            return new GitHubService();
        });
    }

    public function provides()
    {
        return [GitHubService::class];
    }
}

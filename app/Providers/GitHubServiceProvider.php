<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\GitHubService;

class GitHubServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        // Note: GitHub Service must be resolved before using GitHub api!
        $this->app->bind(GitHubService::class, function($app) {
            return new GitHubService();
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

use App\Services\GitHubService;
use App\Services\JenkinsService;
use App\Services\AppStoreConnectService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment([ 'local', 'staging' ])) {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }

        ////////////////////////////////////
        /// register core services
        ////////////////////////////////////
        $this->app->bind(JenkinsService::class, function($app) {
            return new JenkinsService(
                config('jenkins.host'),
                config('jenkins.user'),
                config('jenkins.token')
            );
        });

        $this->app->bind(AppStoreConnectService::class, function($app) {
            return new AppStoreConnectService();
        });

        // Note: GitHub Service must be resolved before using GitHub api!
        $this->app->bind(GitHubService::class, function($app) {
            return new GitHubService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // to fix: "huge paginator icons"
        Paginator::useBootstrap();

        Model::shouldBeStrict(!$this->app->isProduction());
    }
}

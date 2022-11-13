<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

use App\Services\JenkinsService;

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

        $this->app->bind(JenkinsService::class, function($app) {
            return new JenkinsService();
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

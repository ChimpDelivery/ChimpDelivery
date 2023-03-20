<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\S3Service;

class S3ServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton(S3Service::class, function($app) {
            return new S3Service();
        });
    }

    public function boot() : void
    {

    }
}

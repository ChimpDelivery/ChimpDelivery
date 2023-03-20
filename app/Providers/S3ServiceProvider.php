<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

use App\Services\S3Service;

class S3ServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton(S3Service::class, function(Application $app) {
            return new S3Service();
        });
    }

    public function boot() : void
    {

    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Contracts\Foundation\Application;

use App\Services\S3Service;

class S3ServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register() : void
    {
        $this->app->singleton(S3Service::class, function(Application $app) {
            return new S3Service();
        });
    }

    public function provides() : array
    {
        return [S3Service::class];
    }
}

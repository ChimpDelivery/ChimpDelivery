<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;

use App\Services\FtpService;

class FtpServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register() : void
    {
        $this->app->singleton(FtpService::class, function(Application $app) {
            return new FtpService(
                Str::of(config('filesystems.disks.ftp.host'))
                    ->explode('.')
                    ->slice(1)
                    ->prepend('http://www')
                    ->implode('.')
            );
        });
    }

    public function provides() : array
    {
        return [FtpService::class];
    }
}

<?php

use Illuminate\Support\ServiceProvider;

/*
|--------------------------------------------------------------------------
| Autoloaded Service Providers
|--------------------------------------------------------------------------
|
| The service providers listed here will be automatically loaded on the
| request to your application. Feel free to add your own services to
| this array to grant expanded functionality to your applications.
|
*/
return ServiceProvider::defaultProviders()->merge([
        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\HorizonServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        /*
         * 3rd Party Providers
         */
        Spatie\Permission\PermissionServiceProvider::class,

        /*
         * Monitoring Providers
         */
        App\Providers\TelescopeServiceProvider::class,
        App\Providers\HealthServiceProvider::class,
        App\Providers\LogViewerServiceProvider::class,
        App\Providers\LaraLensServiceProvider::class,

        /*
         * Core Providers
         */
        App\Providers\FtpServiceProvider::class,
        App\Providers\S3ServiceProvider::class,
        App\Providers\AppStoreConnectServiceProvider::class,
        App\Providers\GitHubServiceProvider::class,
        App\Providers\JenkinsServiceProvider::class,
    ])->toArray();

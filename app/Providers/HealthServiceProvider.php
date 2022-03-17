<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Spatie\Health\Facades\Health;

use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Encodia\Health\Checks\EnvVars;

class HealthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Health::checks([
            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(70)
                ->failWhenUsedSpaceIsAbovePercentage(90),
            DatabaseCheck::new(),
            DebugModeCheck::new(),
            PingCheck::new()->name('Jenkins Server')->url(config('jenkins.host')),
            ScheduleCheck::new(),
            EnvironmentCheck::new(),
            CacheCheck::new(),
            EnvVars::new()->label('Environment Variables')->requireVarsForEnvironment('local', [
                'APPSTORECONNECT_PRIVATE_KEY',
                'APPSTORECONNECT_ISSUER_ID',
                'APPSTORECONNECT_KID',
                'APPSTORECONNECT_CACHE_DURATION',
                'JENKINS_ENABLED',
                'JENKINS_WS',
                'JENKINS_HOST',
                'JENKINS_USER',
                'JENKINS_TOKEN'
            ])
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}

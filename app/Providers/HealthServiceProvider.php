<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Spatie\Health\Facades\Health;

use Spatie\Health\ResultStores\EloquentHealthResultStore;

use Encodia\Health\Checks\EnvVars;

use Spatie\CpuLoadHealthCheck\CpuLoadCheck;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;

use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseTableSizeCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\QueueCheck;

class HealthServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $tableName = EloquentHealthResultStore::getHistoryItemInstance()->getTable();

        Health::checks([
            CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(2.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(1.5),
            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(70)
                ->failWhenUsedSpaceIsAbovePercentage(90),
            DatabaseCheck::new(),
            DatabaseTableSizeCheck::new()->table($tableName, maxSizeInMb: 50),
            ScheduleCheck::new()->heartbeatMaxAgeInMinutes(2),
            CacheCheck::new(),
            RedisCheck::new(),
            PingCheck::new()->name('Jenkins Server')->url(config('jenkins.host').'/login'),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
            EnvVars::new()->label('Environment Variables')->requireVarsForEnvironment('local', [
                'RECAPTCHA_SITE_KEY',
                'RECAPTCHA_SECRET_KEY',
                'REDIS_CLIENT',
                'AWS_ACCESS_KEY_ID',
                'AWS_SECRET_ACCESS_KEY',
                'AWS_DEFAULT_REGION',
                'AWS_BUCKET',
                'AWS_USE_PATH_STYLE_ENDPOINT',
                'APPSTORECONNECT_CACHE_DURATION',
                'APPSTORECONNECT_ITEM_LIMIT',
                'JENKINS_HOST',
                'JENKINS_USER',
                'JENKINS_TOKEN',
                'GIT_ITEM_LIMIT',
                'FTP_HOST',
                'FTP_USERNAME',
                'FTP_PASSWORD',
                'FTP_ROOT',
                'DISCORD_WEBHOOK_URL',
                'DISCORD_BOT_NAME',
                'SENTRY_LARAVEL_DSN',
                'SENTRY_TRACES_SAMPLE_RATE',
            ]),
            OptimizedAppCheck::new()->checkConfig()->checkRoutes(),
            SecurityAdvisoriesCheck::new(),
            QueueCheck::new(),
        ]);
    }

    public function boot() : void
    {

    }
}

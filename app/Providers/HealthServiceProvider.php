<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Spatie\Health\Facades\Health;

use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseTableSizeCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Encodia\Health\Checks\EnvVars;

use Spatie\Health\ResultStores\EloquentHealthResultStore;

class HealthServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $tableName = EloquentHealthResultStore::getHistoryItemInstance()->getTable();

        Health::checks([
            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(70)
                ->failWhenUsedSpaceIsAbovePercentage(90),
            DatabaseCheck::new(),
            DebugModeCheck::new(),
            PingCheck::new()->name('Jenkins Server')->url(config('jenkins.host').'/login'),
            ScheduleCheck::new()->heartbeatMaxAgeInMinutes(2),
            EnvironmentCheck::new(),
            RedisCheck::new(),
            EnvVars::new()->label('Environment Variables')->requireVarsForEnvironment('local', [
                'RESPONSE_CACHE_DRIVER',
                'RESPONSE_CACHE_ENABLED',
                'CAPTCHA_SECRET',
                'CAPTCHA_SITEKEY',
                'REDIS_CLIENT',
                'AWS_ACCESS_KEY_ID',
                'AWS_SECRET_ACCESS_KEY',
                'AWS_DEFAULT_REGION',
                'AWS_BUCKET',
                'AWS_USE_PATH_STYLE_ENDPOINT',
                'APPSTORECONNECT_PRIVATE_KEY',
                'APPSTORECONNECT_ISSUER_ID',
                'APPSTORECONNECT_KID',
                'APPSTORECONNECT_CACHE_DURATION',
                'APPSTORECONNECT_ITEM_LIMIT',
                'APPSTORECONNECT_COMPANY_NAME',
                'APPSTORECONNECT_BUNDLE_PREFIX',
                'APPSTORECONNECT_USER_EMAIL',
                'APPSTORECONNECT_USER_PASS',
                'JENKINS_WS',
                'JENKINS_HOST',
                'JENKINS_USER',
                'JENKINS_TOKEN',
                'GIT_ORGANIZATION_NAME',
                'GIT_ACCESS_TOKEN',
                'GIT_ITEM_LIMIT',
                'GIT_TEMPLATE_PROJECT',
                'GIT_PROTOTYPE_TOPIC',
                'DISCORD_WEBHOOK_URL',
                'DISCORD_BOT_NAME',
                'AUTH_INVITE_CODE'
            ]),
            DatabaseTableSizeCheck::new()->table($tableName, maxSizeInMb: 50_000)
        ]);
    }

    public function boot() : void
    {

    }
}

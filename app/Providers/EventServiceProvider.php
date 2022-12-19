<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\AppChanged;
use App\Events\WorkspaceChanged;
use App\Listeners\ChangeWorkspaceSettings;
use App\Actions\Api\Jenkins\Post\ScanOrganization;
use App\Actions\Api\S3\Provision\Post\UploadAppIcon;
use App\Actions\Api\S3\Provision\Post\UploadAppStoreConnectSign;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [

        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        WorkspaceChanged::class => [
            ChangeWorkspaceSettings::class,
            UploadAppStoreConnectSign::class,
        ],

        AppChanged::class => [
            UploadAppIcon::class,
            ScanOrganization::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

use App\Models\Workspace;
use App\Events\AppChanged;
use App\Events\WorkspaceChanged;
use App\Observers\WorkspaceObserver;
use App\Listeners\S3\UploadAppIcon;
use App\Listeners\UpdateWorkspaceSettings;
use App\Listeners\S3\UploadAppStoreConnectSign;
use App\Listeners\S3\UploadGooglePlayCredentials;
use App\Actions\Api\Jenkins\ScanOrganization;

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
            UpdateWorkspaceSettings::class,
            UploadAppStoreConnectSign::class,
            UploadGooglePlayCredentials::class,
        ],

        AppChanged::class => [
            UploadAppIcon::class,
            ScanOrganization::class,
        ],
    ];

    protected $observers = [

        Workspace::class => [
            WorkspaceObserver::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     */
    public function boot() : void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     */
    public function shouldDiscoverEvents() : bool
    {
        return false;
    }
}

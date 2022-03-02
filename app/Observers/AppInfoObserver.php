<?php

namespace App\Observers;

use App\Models\AppInfo;

class AppInfoObserver
{
    /**
     * Handle the AppInfo "created" event.
     *
     * @param  \App\Models\AppInfo  $appInfo
     * @return void
     */
    public function created(AppInfo $appInfo)
    {
        //
    }

    /**
     * Handle the AppInfo "updated" event.
     *
     * @param  \App\Models\AppInfo  $appInfo
     * @return void
     */
    public function updated(AppInfo $appInfo)
    {
        //
    }

    /**
     * Handle the AppInfo "deleted" event.
     *
     * @param  \App\Models\AppInfo  $appInfo
     * @return void
     */
    public function deleted(AppInfo $appInfo)
    {
        $appInfo->update([
            'app_name' => time() . '::' . $appInfo->app_name,
            'app_bundle' => time() . '::' . $appInfo->app_bundle,
            'appstore_id' => time() . '::' . $appInfo->appstore_id
        ]);
    }

    /**
     * Handle the AppInfo "restored" event.
     *
     * @param  \App\Models\AppInfo  $appInfo
     * @return void
     */
    public function restored(AppInfo $appInfo)
    {
        //
    }

    /**
     * Handle the AppInfo "force deleted" event.
     *
     * @param  \App\Models\AppInfo  $appInfo
     * @return void
     */
    public function forceDeleted(AppInfo $appInfo)
    {
        //
    }
}

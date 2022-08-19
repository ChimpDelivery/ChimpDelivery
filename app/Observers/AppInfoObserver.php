<?php

namespace App\Observers;

use App\Models\AppInfo;

class AppInfoObserver
{
    public function created(AppInfo $appInfo)
    { }

    public function updated(AppInfo $appInfo)
    { }

    public function deleted(AppInfo $appInfo)
    { }

    public function restored(AppInfo $appInfo)
    { }

    public function forceDeleted(AppInfo $appInfo)
    { }
}

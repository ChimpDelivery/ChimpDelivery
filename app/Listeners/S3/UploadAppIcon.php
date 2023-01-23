<?php

namespace App\Listeners\S3;

use Illuminate\Support\Facades\Cache;

use App\Events\AppChanged;
use App\Traits\AsS3Client;

class UploadAppIcon
{
    use AsS3Client;

    public function handle(AppChanged $event) : void
    {
        if ($event->request->hasFile('app_icon'))
        {
            $uploadedIcon = $this->UploadToS3($event->request->validated('app_icon'));
            if ($uploadedIcon)
            {
                Cache::forget($event->appInfo->app_icon);

                $event->appInfo->fill([ 'app_icon' => $uploadedIcon ]);
                $event->appInfo->save();
            }
        }
    }
}

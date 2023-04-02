<?php

namespace App\Listeners\S3;

use App\Traits\AsS3Client;
use App\Events\AppChanged;

class UploadAppIcon
{
    use AsS3Client;

    public function handle(AppChanged $event) : void
    {
        if ($event->inputs->has('app_icon'))
        {
            $uploadedIcon = $this->UploadToS3($event->inputs->app_icon);
            if ($uploadedIcon)
            {
                $event->appInfo->fill(['app_icon' => $uploadedIcon])->save();
            }
        }
    }
}

<?php

namespace App\Listeners\S3;

use App\Traits\AsS3Client;
use App\Events\AppChanged;

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
                $event->appInfo->fill([ 'app_icon' => $uploadedIcon ]);
                $event->appInfo->save();
            }
        }
    }
}

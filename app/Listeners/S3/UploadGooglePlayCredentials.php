<?php

namespace App\Listeners\S3;

use App\Traits\AsS3Client;
use App\Events\WorkspaceChanged;

class UploadGooglePlayCredentials
{
    use AsS3Client;

    public function handle(WorkspaceChanged $event) : void
    {
        $googlePlaySetting = $event->workspace->googlePlaySetting()->firstOrCreate();

        if ($event->inputs->has('service_account'))
        {
            $uploadedAccount = $this->UploadToS3($event->inputs->service_account);
            if ($uploadedAccount)
            {
                $googlePlaySetting->fill(['service_account' => $uploadedAccount]);
            }
        }

        if ($event->inputs->has('keystore_file'))
        {
            $uploadedKeystore = $this->UploadToS3($event->inputs->keystore_file);
            if ($uploadedKeystore)
            {
                $googlePlaySetting->fill(['keystore_file' => $uploadedKeystore]);
            }
        }

        $googlePlaySetting->save();
    }
}

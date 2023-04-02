<?php

namespace App\Listeners\S3;

use App\Traits\AsS3Client;
use App\Events\WorkspaceChanged;

class UploadAppStoreConnectSign
{
    use AsS3Client;

    public function handle(WorkspaceChanged $event) : void
    {
        $appStoreConnectSign = $event->workspace->appStoreConnectSign()->firstOrCreate();

        if ($event->inputs->has('provision_profile'))
        {
            $uploadedProfile = $this->UploadToS3($event->inputs->provision_profile);
            if ($uploadedProfile)
            {
                $appStoreConnectSign->fill([ 'provision_profile' => $uploadedProfile ]);
            }
        }

        if ($event->inputs->has('cert'))
        {
            $uploadedCert = $this->UploadToS3($event->inputs->cert);
            if ($uploadedCert)
            {
                $appStoreConnectSign->fill([ 'cert' => $uploadedCert ]);
            }
        }

        $appStoreConnectSign->save();
    }
}

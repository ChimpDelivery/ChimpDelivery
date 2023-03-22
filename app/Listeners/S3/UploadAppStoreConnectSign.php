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

        if ($event->request->hasFile('provision_profile'))
        {
            $uploadedProfile = $this->UploadToS3($event->request->validated('provision_profile'));
            if ($uploadedProfile)
            {
                $appStoreConnectSign->fill([ 'provision_profile' => $uploadedProfile ]);
            }
        }

        if ($event->request->hasFile('cert'))
        {
            $uploadedCert = $this->UploadToS3($event->request->validated('cert'));
            if ($uploadedCert)
            {
                $appStoreConnectSign->fill([ 'cert' => $uploadedCert ]);
            }
        }

        $appStoreConnectSign->save();
    }
}

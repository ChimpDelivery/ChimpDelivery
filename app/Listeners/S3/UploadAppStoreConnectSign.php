<?php

namespace App\Listeners\S3;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;

use App\Events\WorkspaceChanged;
use App\Traits\AsS3Client;
use App\Traits\AsActionResponse;

class UploadAppStoreConnectSign
{
    use AsAction;
    use AsActionResponse;
    use AsS3Client;

    public function handle(WorkspaceChanged $event) : void
    {
        $appStoreConnectSign = Auth::user()->workspace->appStoreConnectSign()->firstOrCreate();

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

    public function authorize() : bool
    {
        return Auth::user()->can('update workspace');
    }
}

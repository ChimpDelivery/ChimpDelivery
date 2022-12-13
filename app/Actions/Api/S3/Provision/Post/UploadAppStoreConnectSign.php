<?php

namespace App\Actions\Api\S3\Provision\Post;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;

use App\Services\S3Service;
use App\Traits\AsActionResponse;
use App\Events\WorkspaceChanged;
use App\Models\AppStoreConnectSign;

class UploadAppStoreConnectSign
{
    use AsAction;
    use AsActionResponse;

    public function handle(WorkspaceChanged $event) : void
    {
        $appStoreConnectSign = AppStoreConnectSign::firstOrCreate([
            'workspace_id' => $event->workspace->id
        ]);

        $s3Service = app(S3Service::class);

        if ($event->request->hasFile('provision_profile'))
        {
            $appStoreConnectSign->fill([
                'provision_profile' => $s3Service->UploadFile(
                    $event->request->validated('provision_profile')
                )
            ]);
        }

        if ($event->request->hasFile('cert'))
        {
            $appStoreConnectSign->fill([
                'cert' => $s3Service->UploadFile(
                    $event->request->validated('cert')
                )
            ]);
        }

        $appStoreConnectSign->save();
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew() && Auth::user()->hasRole('Admin_Workspace');
    }
}

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

    private const BUCKET_ROOT_FOLDER = "Workspace";

    public function handle(WorkspaceChanged $event) : array
    {
        $appStoreConnectSign = AppStoreConnectSign::firstOrCreate([
            'workspace_id' => $event->workspace->id
        ]);

        $s3Service = app(S3Service::class);

        if ($event->request->hasFile('provision_profile'))
        {
            $provisionFile = $event->request->validated('provision_profile');
            $uploadedPath = $s3Service->UploadProvision($provisionFile->getClientOriginalName(), $provisionFile);

            $appStoreConnectSign->update([
                'provision_profile' => $uploadedPath,
            ]);
        }

        if ($event->request->hasFile('cert'))
        {
            $certFile = $event->request->validated('cert');
            $uploadedPath = $s3Service->UploadCert($certFile->getClientOriginalName(), $certFile);

            $appStoreConnectSign->update([
                'cert' => $uploadedPath,
            ]);
        }

        return [
            'success' => true,
            'message' => 'AppStoreConnect App Signing updated. ',
        ];
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew() && Auth::user()->hasRole('Admin_Workspace');
    }
}

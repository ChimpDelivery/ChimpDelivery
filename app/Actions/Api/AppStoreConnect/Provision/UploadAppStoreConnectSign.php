<?php

namespace App\Actions\Api\AppStoreConnect\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Events\WorkspaceChanged;
use App\Models\AppStoreConnectSign;

class UploadAppStoreConnectSign
{
    use AsAction;

    public function handle(WorkspaceChanged $event) : void
    {
        $targetWorkspace = $event->workspace;

        $appStoreConnectSign = AppStoreConnectSign::firstOrCreate([
            'workspace_id' => $targetWorkspace->id
        ]);

        if ($event->request->hasFile('provision_profile'))
        {
            $uploadedProvisionFile = $event->request->file('provision_profile');

            $provisionFilePath = "Workspace/{$targetWorkspace->id}/provisions";
            $provisionFileName = $uploadedProvisionFile->getClientOriginalName();

            $path = Storage::disk('s3')->putFileAs(
                $provisionFilePath,
                $uploadedProvisionFile,
                $provisionFileName,
            );

            $appStoreConnectSign->update([
                'provision_profile' => Storage::disk('s3')->url($path),
            ]);
        }
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }
}

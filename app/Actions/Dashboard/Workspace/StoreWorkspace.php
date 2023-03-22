<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Validation\Validator;
use Illuminate\Http\RedirectResponse;

use App\Models\Workspace;
use App\Events\WorkspaceChanged;
use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

class StoreWorkspace
{
    use AsAction;

    public function handle(StoreWorkspaceSettingsRequest $request) : RedirectResponse
    {
        $workspace = $this->StoreOrUpdate($request);

        $flashMessageDetail = $workspace->wasRecentlyCreated ? 'created.' : 'updated.';
        $flashMessage = "Workspace: <b>{$workspace->name}</b> {$flashMessageDetail}";

        return to_route('workspace_settings')->with('success', $flashMessage);
    }

    private function StoreOrUpdate(StoreWorkspaceSettingsRequest $request) : Workspace
    {
        $user = $request->user();

        $targetWorkspace = $user->isNew() ? new Workspace() : $user->workspace;
        $targetWorkspace->fill($request->safe()->only([ 'name' ]));
        $targetWorkspace->save();

        event(new WorkspaceChanged($user, $targetWorkspace, $request));

        return $targetWorkspace;
    }

    public function withValidator(Validator $validator, StoreWorkspaceSettingsRequest $request)
    {
        $validator->after(function (Validator $validator) use ($request) {
            $this->ValidateCertificate($validator, $request);
            $this->ValidateProvision($validator, $request);
        });
    }

    private function ValidateCertificate(Validator $validator, StoreWorkspaceSettingsRequest $request) : void
    {
        if ($request->hasFile('cert'))
        {
            $isValidCert = $this->IsValidFile(
                file: $request->validated('cert'),
                serverMime: 'application/octet-stream',
                clientMime: 'application/x-pkcs12',
                clientExt: '.p12'
            );

            if (!$isValidCert)
            {
                $validator->errors()->add(
                    'cert',
                    'Invalid certificate type! Only ".p12" files allowed.'
                );
            }
        }
    }

    private function ValidateProvision(Validator $validator, StoreWorkspaceSettingsRequest $request) : void
    {
        if ($request->hasFile('provision_profile'))
        {
            $isValidProvision = $this->IsValidFile(
                file: $request->validated('provision_profile'),
                serverMime: 'application/octet-stream',
                clientMime: 'application/octet-stream',
                clientExt: '.mobileprovision'
            );

            if (!$isValidProvision)
            {
                $validator->errors()->add(
                    'provision_profile',
                    'Invalid provision profile file! Only ".mobileprovision" files allowed.'
                );
            }
        }
    }

    private function IsValidFile($file, $serverMime, $clientMime, $clientExt) : bool
    {
        return $file->getMimeType() === $serverMime
            && $file->getClientMimeType() === $clientMime
            && $this->IsValidExtension($file, $clientExt);
    }

    private function IsValidExtension($file, $extension) : bool
    {
        return str($file->getClientOriginalName())->endsWith($extension);
    }

    public function authorize(StoreWorkspaceSettingsRequest $request) : bool
    {
        $user = $request->user();

        return $user->can($user->isNew() ? 'create workspace' : 'update workspace');
    }
}

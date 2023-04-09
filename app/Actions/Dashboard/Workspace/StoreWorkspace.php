<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;

use App\Models\User;
use App\Models\Workspace;
use App\Events\WorkspaceChanged;
use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

class StoreWorkspace
{
    use AsAction;

    public function handle(User $user, Workspace $workspace, ValidatedInput $inputs) : RedirectResponse
    {
        $workspace->fill($inputs->only(['name']))->save();

        event(new WorkspaceChanged($user, $workspace, $inputs));

        $flashMessageDetail = $workspace->wasRecentlyCreated ? 'created.' : 'updated.';
        $flashMessage = "Workspace: <b>{$workspace->name}</b> {$flashMessageDetail}";

        return to_route('workspace_settings')->with('success', $flashMessage);
    }

    public function asController(StoreWorkspaceSettingsRequest $request) : RedirectResponse
    {
        $user = $request->user();

        return $this->handle(
            $user,
            $user->isNew() ? new Workspace() : $user->workspace,
            $request->safe()
        );
    }

    public function withValidator(Validator $validator, StoreWorkspaceSettingsRequest $request) : void
    {
        $validator->after(function (Validator $validator) use ($request) {
            $this->ValidateCertificate($validator, $request);
            $this->ValidateProvision($validator, $request);
            $this->ValidateServiceAccount($validator, $request);
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

    private function ValidateServiceAccount(Validator $validator, StoreWorkspaceSettingsRequest $request) : void
    {
        if ($request->hasFile('service_account'))
        {
            $isValidCert = $this->IsValidFile(
                file: $request->validated('service_account'),
                serverMime: 'application/json',
                clientMime: 'application/json',
                clientExt: '.json'
            );

            if (!$isValidCert)
            {
                $validator->errors()->add(
                    'service_account',
                    'Invalid service account file! Only ".json" files allowed.'
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
        return Str::of($file->getClientOriginalName())->endsWith($extension);
    }

    public function authorize(StoreWorkspaceSettingsRequest $request) : bool
    {
        $user = $request->user();

        return $user->can($user->isNew() ? 'create workspace' : 'update workspace');
    }
}

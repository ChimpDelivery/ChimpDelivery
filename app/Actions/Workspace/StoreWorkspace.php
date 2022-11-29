<?php

namespace App\Actions\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

use App\Models\Workspace;
use App\Events\WorkspaceChanged;
use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

class StoreWorkspace
{
    use AsAction;

    private bool $isNewUser;

    public function handle(StoreWorkspaceSettingsRequest $request) : RedirectResponse
    {
        $response = $this->StoreOrUpdate($request);
        $workspaceName = $response['response']->name;

        $flashMessageDetail = ($response['wasRecentlyCreated'] === true) ? 'created.' : 'updated.';
        $flashMessage = "Workspace: <b>{$workspaceName}</b> {$flashMessageDetail}";

        return to_route('workspace_settings')->with('success', $flashMessage);
    }

    public function StoreOrUpdate(StoreWorkspaceSettingsRequest $request) : array
    {
        $targetWorkspace = ($this->isNewUser)
            ? new Workspace()
            : Auth::user()->workspace;

        event(new WorkspaceChanged($targetWorkspace, $request));

        return [
            'response' => $targetWorkspace,
            'wasRecentlyCreated' => $this->isNewUser,
        ];
    }

    public function withValidator(Validator $validator, StoreWorkspaceSettingsRequest $request)
    {
        $validator->after(function (Validator $validator) use ($request) {
            if ($request->hasFile('cert')
                && $request->file('cert')->getClientMimeType() !== 'application/x-pkcs12')
            {
                $validator->errors()->add(
                    'cert',
                    'Invalid certificate type! Only .p12 certificates allowed.'
                );
            }

            if ($request->hasFile('provision_profile'))
            {
                $provisionFile = $request->validated('provision_profile');
                $isValidProvisionFile = $provisionFile->getClientMimeType() === 'application/octet-stream'
                    && Str::of($provisionFile->getClientOriginalName())->endsWith('.mobileprovision');

                if (!$isValidProvisionFile)
                {
                    $validator->errors()->add(
                        'provision_profile',
                        'Invalid provision profile type!'
                    );
                }
            }
        });
    }

    public function authorize() : bool
    {
        $this->isNewUser = Auth::user()->isNew();

        return Auth::user()->can(($this->isNewUser) ? 'create workspace' : 'update workspace');
    }
}

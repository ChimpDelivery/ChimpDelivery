<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Validation\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;

use App\Validators\FileValidator;

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
            $fileValidator = new FileValidator($validator, $request);
            $fileValidator->ValidateFile('cert', 'application/octet-stream', 'application/x-pkcs12', '.p12');
            $fileValidator->ValidateFile('provision_profile', 'application/octet-stream', 'application/octet-stream', '.mobileprovision');
            $fileValidator->ValidateFile('service_account', 'application/json', 'application/json', '.json');
            $fileValidator->ValidateFile('keystore_file', 'application/x-java-keystore', 'application/octet-stream', '.keystore');
        });
    }

    public function authorize(StoreWorkspaceSettingsRequest $request) : bool
    {
        $user = $request->user();

        return $user->can($user->isNew() ? 'create workspace' : 'update workspace');
    }
}

<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

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

    public function authorize(StoreWorkspaceSettingsRequest $request) : bool
    {
        $user = $request->user();

        return $user->can($user->isNew() ? 'create workspace' : 'update workspace');
    }
}

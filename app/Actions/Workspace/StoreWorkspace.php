<?php

namespace App\Actions\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use App\Models\Workspace;
use App\Events\WorkspaceChanged;
use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

class StoreWorkspace
{
    use AsAction;

    public function handle(StoreWorkspaceSettingsRequest $request) : RedirectResponse
    {
        $response = $this->StoreOrUpdate($request);
        $workspaceName = $response['response']->name;

        $flashMessageDetail = $response['wasRecentlyCreated'] === true ? 'created.' : 'updated.';
        $flashMessage = "Workspace: <b>{$workspaceName}</b> {$flashMessageDetail}";

        return to_route('workspace_settings')->with('success', $flashMessage);
    }

    public function StoreOrUpdate(StoreWorkspaceSettingsRequest $request) : array
    {
        // check new user
        $currentWorkspace = Auth::user()->workspace;
        $isNewUser = $currentWorkspace->id === Workspace::$DEFAULT_WORKSPACE_ID;

        // gate
        $method = $isNewUser ? 'create' : 'update';
        $action = $isNewUser ? Workspace::class : $currentWorkspace;

        //
        $targetWorkspace = ($isNewUser) ? new Workspace() : $currentWorkspace;

        event(new WorkspaceChanged($targetWorkspace, $request));

        return [
            'response' => $targetWorkspace,
            'wasRecentlyCreated' => $isNewUser,
        ];
    }

    public function authorize() : bool
    {
        return Auth::user()->can('update workspace');
    }
}

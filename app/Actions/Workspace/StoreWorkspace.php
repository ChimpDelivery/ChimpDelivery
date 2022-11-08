<?php

namespace App\Actions\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;

use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

use App\Http\Controllers\Api\WorkspaceController;

class StoreWorkspace
{
    use AsAction;

    public function handle(StoreWorkspaceSettingsRequest $request) : RedirectResponse
    {
        $response = app(WorkspaceController::class)->StoreOrUpdate($request)->getData();
        $flashMessage = "Workspace: <b>{$response->response->name}</b> " . (($response->wasRecentlyCreated) ? 'created.' : 'updated.');
        session()->flash('success', $flashMessage);

        return to_route('workspace_settings');
    }
}

<?php

namespace App\Actions\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;

use App\Http\Requests\Workspace\JoinWorkspaceRequest;

use App\Http\Controllers\Api\WorkspaceController;

class JoinWorkspace
{
    use AsAction;

    public function handle(JoinWorkspaceRequest $request) : RedirectResponse
    {
        $workspace = app(WorkspaceController::class)->JoinWorkspace($request);

        return to_route('index');
    }
}

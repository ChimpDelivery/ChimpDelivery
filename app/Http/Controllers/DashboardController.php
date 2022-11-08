<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\WorkspaceController;

use App\Http\Requests\Workspace\JoinWorkspaceRequest;

use Illuminate\Contracts\View\View;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function GetJoinWorkspaceForm() : View
    {
        return view('workspace-join');
    }

    public function PostJoinWorkspaceForm(JoinWorkspaceRequest $request) : RedirectResponse
    {
        $workspace = app(WorkspaceController::class)->JoinWorkspace($request);

        return to_route('index');
    }
}

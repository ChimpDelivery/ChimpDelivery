<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use App\Models\WorkspaceInviteCode;
use App\Http\Requests\Workspace\JoinWorkspaceRequest;

class JoinWorkspace
{
    use AsAction;

    public function handle(JoinWorkspaceRequest $request) : RedirectResponse
    {
        $inviteCode = WorkspaceInviteCode::where('code', '=', $request->invite_code)->first();

        return to_route('index');
    }

    public function authorize(JoinWorkspaceRequest $request) : bool
    {
        return Auth::user()->can('join workspace');
    }
}

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
        $code = WorkspaceInviteCode::whereBlind('code', 'code', $request->validated('invite_code'))->first();

        if (!$code)
        {
            return to_route('workspace_join')->withErrors('Invite Code is invalid!');
        }

        return to_route('index');
    }

    public function authorize() : bool
    {
        return Auth::user()->can('join workspace');
    }
}

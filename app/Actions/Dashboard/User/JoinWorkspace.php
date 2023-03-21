<?php

namespace App\Actions\Dashboard\User;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;

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
            return to_route('workspace_join')->withErrors('Invitation Code is invalid!');
        }

        $user = $request->user();
        if (!$user->update([ 'workspace_id' => $code->workspace_id ]))
        {
            return to_route('index')->withErrors('User Workspace can not be changed at that time, wait...');
        }

        $user->syncRoles([ 'User_Workspace' ]);

        return to_route('index')->with(
            'success',
            "You have joined the <b>{$code->workspace->name} Workspace</b>, congratulations!"
        );
    }

    public function authorize(JoinWorkspaceRequest $request) : bool
    {
        return $request->user()->can('join workspace');
    }
}

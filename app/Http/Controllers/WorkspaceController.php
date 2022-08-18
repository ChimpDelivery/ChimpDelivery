<?php

namespace App\Http\Controllers;

use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

use App\Models\Workspace;
use App\Models\AppStoreConnectSetting;
use App\Models\AppleSetting;
use App\Models\GithubSetting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function Get() : Workspace
    {
        $workspace = Auth::user()->workspace;
        $this->authorize('view', $workspace);

        return $workspace;
    }

    public function Store(StoreWorkspaceSettingsRequest $request) : JsonResponse
    {
        $this->authorize('create', Workspace::class);

        $validated = $request->safe();

        $newWorkspace = Workspace::create(
            $validated->only([
                'name',
                'api_key',
            ])
        );

        $newAppStoreConnectSetting = AppStoreConnectSetting::create([
            'workspace_id' => $newWorkspace->id,
            'private_key' => ($request->hasFile('private_key')) ? $validated->private_key->get() : null,
        ]);

        $newAppStoreConnectSetting->update(
            $validated->only([
                'workspace_id',
                'issuer_id',
                'kid',
            ])
        );

        $newAppleSetting = AppleSetting::create([ 'workspace_id' => $newWorkspace->id ]);
        $newAppleSetting->update(
            $validated->only([
                'workspace_id',
                'usermail',
                'app_specific_pass',
            ])
        );

        $newGitSetting = GithubSetting::create([ 'workspace_id' => $newWorkspace->id ]);
        $newGitSetting->update(
            $validated->only([
                'workspace_id',
                'personal_access_token',
                'organization_name',
                'template_name',
                'topic_name',
            ])
        );

        Auth::user()->update([ 'workspace_id' => $newWorkspace->id ]);
        Auth::user()->syncRoles([ 'Admin_Workspace' ]);

        return response()->json([ 'response' => $newWorkspace ], Response::HTTP_ACCEPTED);
    }

    public function Update(StoreWorkspaceSettingsRequest $request) : JsonResponse
    {
        $workspace = Auth::user()->workspace;
        $this->authorize('update', $workspace);

        $response = $workspace->update($request->safe()->only([ 'name', 'api_key' ]));

        return response()->json([ 'status' => $response ], Response::HTTP_ACCEPTED);
    }
}

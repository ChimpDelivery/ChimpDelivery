<?php

namespace App\Http\Controllers;

use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;
use App\Http\Requests\Workspace\UpdateWorkspaceSettingsRequest;

use App\Models\Workspace;
use App\Models\AppStoreConnectSetting;
use App\Models\AppleSetting;
use App\Models\GithubSetting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function Get() : JsonResponse
    {
        $workspace = Auth::user()->workspace;
        $this->authorize('view', $workspace);

        return response()->json($workspace);
    }

    public function Store(StoreWorkspaceSettingsRequest $request) : JsonResponse
    {
        $this->authorize('create', Workspace::class);

        $newWorkspace = Workspace::create($request->safe()->only([
            'name',
            'api_key',
        ]));

        $newAppStoreConnectSetting = AppStoreConnectSetting::create([ 'workspace_id' => $newWorkspace->id ]);
        $newAppStoreConnectSetting->update(
            $request->safe()->only([
                'workspace_id',
                'private_key',
                'issuer_id',
                'kid',
            ])
        );

        $newAppleSetting = AppleSetting::create([ 'workspace_id' => $newWorkspace->id ]);
        $newAppleSetting->update(
            $request->safe()->only([
                'workspace_id',
                'usermail',
                'app_specific_pass'
            ])
        );

        $newGitSetting = GithubSetting::create([ 'workspace_id' => $newWorkspace->id ]);
        $newGitSetting->update(
            $request->safe()->only([
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

    public function Update(UpdateWorkspaceSettingsRequest $request) : JsonResponse
    {
        $workspace = Auth::user()->workspace;
        $this->authorize('update', $workspace);
        $response = $workspace->update($request->all());

        return response()->json([ 'status' => $response ], Response::HTTP_ACCEPTED);
    }
}

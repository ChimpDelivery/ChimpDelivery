<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Events\WorkspaceChanged;
use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

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

    public function StoreOrUpdate(StoreWorkspaceSettingsRequest $request) : JsonResponse
    {
        // check new user
        $currentWorkspace = Auth::user()->workspace;
        $isNewUser = $currentWorkspace->id == 1;

        // gate
        $method = $isNewUser ? 'create' : 'update';
        $action = $isNewUser ? Workspace::class : $currentWorkspace;
        $this->authorize($method, $action);

        //
        $targetWorkspace = ($isNewUser) ? new Workspace() : $currentWorkspace;

        event(new WorkspaceChanged($targetWorkspace, $request));

        return response()->json([
            'response' => $targetWorkspace,
            'wasRecentlyCreated' => $isNewUser
        ], Response::HTTP_ACCEPTED);
    }
}

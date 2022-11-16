<?php

namespace App\Http\Controllers\Api;

use App\Events\WorkspaceChanged;

use App\Http\Controllers\Controller;

use App\Http\Requests\Workspace\JoinWorkspaceRequest;
use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

use App\Models\Workspace;

use App\Models\WorkspaceInviteCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function Get() : Workspace
    {
        $workspace = Auth::user()->workspace;

        return $workspace;
    }

    public function JoinWorkspace(JoinWorkspaceRequest $request) : JsonResponse
    {
        $inviteCode = WorkspaceInviteCode::where('code', '=', $request->invite_code)->first();

        return response()->json([
            'status' => 200
        ], Response::HTTP_ACCEPTED);
    }

    public function StoreOrUpdate(StoreWorkspaceSettingsRequest $request) : JsonResponse
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

        return response()->json([
            'response' => $targetWorkspace,
            'wasRecentlyCreated' => $isNewUser
        ], Response::HTTP_ACCEPTED);
    }
}

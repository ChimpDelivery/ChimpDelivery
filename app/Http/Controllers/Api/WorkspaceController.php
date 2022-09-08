<?php

namespace App\Http\Controllers\Api;

use App\Events\WorkspaceChanged;

use App\Http\Controllers\Controller;

use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

use App\Models\Workspace;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function Get() : Workspace
    {
        return Auth::user()->workspace;
    }

    public function StoreOrUpdate(StoreWorkspaceSettingsRequest $request) : JsonResponse
    {
        // check new user
        $currentWorkspace = Auth::user()->workspace;
        $isNewUser = $currentWorkspace->id === 1;

        //
        $targetWorkspace = ($isNewUser) ? new Workspace() : $currentWorkspace;

        event(new WorkspaceChanged($targetWorkspace, $request));

        return response()->json([
            'response' => $targetWorkspace,
            'wasRecentlyCreated' => $isNewUser
        ], Response::HTTP_ACCEPTED);
    }
}
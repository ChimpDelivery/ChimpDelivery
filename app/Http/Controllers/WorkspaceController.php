<?php

namespace App\Http\Controllers;

use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;
use App\Http\Requests\Workspace\UpdateWorkspaceSettingsRequest;

use App\Models\Workspace;

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
        $newWorkspace = Workspace::create($request->all());

        Auth::user()->update([ 'workspace_id' => $newWorkspace->id ]);

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

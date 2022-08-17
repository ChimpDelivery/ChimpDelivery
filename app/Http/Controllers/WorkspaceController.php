<?php

namespace App\Http\Controllers;

use App\Http\Requests\Workspace\StoreWsSettingsRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function UpdateWorkspace(StoreWsSettingsRequest $request) : JsonResponse
    {
        $workspace = Auth::user()->workspace;
        $this->authorize('update', $workspace);

        $response = $workspace->update($request->all());

        return response()->json([ 'status' => $response ], Response::HTTP_ACCEPTED);
    }
}

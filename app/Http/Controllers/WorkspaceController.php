<?php

namespace App\Http\Controllers;

use App\Http\Requests\Workspace\StoreWsSettingsRequest;

use App\Models\Workspace;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function UpdateWorkspace(StoreWsSettingsRequest $request) : JsonResponse
    {
        $response = Workspace::find(Auth::user()->workspace->id)->update($request->all());

        return response()->json([ 'status' => $response ], Response::HTTP_ACCEPTED);
    }
}

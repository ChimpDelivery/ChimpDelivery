<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\AppInfo\GetAppInfoRequest;

use App\Models\AppInfo;

class AppInfoController extends Controller
{
    public function DeleteApp(GetAppInfoRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));

        $this->authorize('delete', $app);

        $app->delete();

        return response()->json(['message' => "Project: <b>{$app->project_name}</b> deleted."], Response::HTTP_OK);
    }
}

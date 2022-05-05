<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AppInfoController extends Controller
{
    public function GetApp(Request $request) : JsonResponse
    {
        $response = AppInfo::find($request->id, [
            'app_bundle',
            'app_name',
            'fb_app_id',
            'elephant_id',
            'elephant_secret'
        ]);

        return response()->json($response, Response::HTTP_ACCEPTED);
    }

    public function DeleteApp(Request $request) : JsonResponse
    {
        $appInfo = AppInfo::find($request->id);

        if ($appInfo)
        {
            $appInfo->delete();
            return response()->json([
                'message' => "App: {$appInfo->app_name} deleted."
            ], Response::HTTP_ACCEPTED);
        }

        return response()->json([
            'message' => 'App not found!'
        ], Response::HTTP_FORBIDDEN);
    }
}

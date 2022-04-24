<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppInfoController extends Controller
{
    public function GetSpecificApp(Request $request) : JsonResponse
    {
        $response = AppInfo::find($request->id, [
            'app_bundle',
            'app_name',
            'fb_app_id',
            'elephant_id',
            'elephant_secret'
        ]);

        return response()->json($response);
    }
}

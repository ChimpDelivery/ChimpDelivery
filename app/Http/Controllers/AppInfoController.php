<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfoRequest;

use App\Models\AppInfo;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppInfoController extends Controller
{

    public function Index(Request $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }

    public function Create(AppInfoRequest $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }


    public function Store(AppInfoRequest $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }


    public function Show(Request $request): JsonResponse
    {
        return response()->json([
            'app_info' => AppInfo::find($request->id)
        ]);
    }


    public function Edit(AppInfoRequest $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }


    public function Update(AppInfoRequest $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }


    public function Destroy(AppInfoRequest $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }
}

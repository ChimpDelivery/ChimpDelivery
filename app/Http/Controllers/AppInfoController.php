<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfoRequest;

use App\Models\AppInfo;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppInfoController extends Controller
{

    public function index(Request $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }

    public function create(AppInfoRequest $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }


    public function store(AppInfoRequest $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }


    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'app_info' => AppInfo::find($request->id)
        ]);
    }


    public function edit(AppInfoRequest $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }


    public function update(AppInfoRequest $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }


    public function destroy(AppInfoRequest $request)
    {
        return response()->json([
            'not_implemented' => true
        ]);
    }
}

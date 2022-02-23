<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfoRequest;
use App\Models\AppInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppInfoController extends Controller
{

    public function index(Request $request)
    {

    }


    public function create(AppInfoRequest $request)
    {
        $validated = $request->validated();
        if ($validated)
        {
            $appInfo = new AppInfo();
            $appInfo->app_name = $request->app_name;
            $appInfo->app_bundle = $request->app_bundle;
            $appInfo->fb_app_id = $request->fb_app_id;
            $appInfo->elephant_id = $request->elephant_id;
            $appInfo->elephant_secret = $request->elephant_secret;
            $appInfo->save();

            return response()->json([
                'status_code' => 200
            ]);
        }
        else
        {
            return response()->json([
                'status_code' => 400
            ]);
        }
    }


    public function store(AppInfoRequest $request)
    {
        $validated = $request->all();

        $appInfo = new AppInfo();
        $appInfo->app_name = $validated['app_name'];
        $appInfo->app_bundle = $validated['app_bundle'];
        $appInfo->fb_app_id = $validated['fb_app_id'];
        $appInfo->elephant_id = $validated['elephant_id'];
        $appInfo->elephant_secret = $validated['elephant_secret'];
        $appInfo->save();

        return response()->json([
            'status_code' => 200
        ]);
    }


    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'app_info' => AppInfo::find($request->id)
        ]);
    }


    public function edit(AppInfo $appInfo)
    {

    }


    public function update(Request $request, AppInfo $appInfo)
    {

    }


    public function destroy(AppInfo $appInfo)
    {

    }
}

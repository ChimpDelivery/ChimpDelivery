<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppInfoController extends Controller
{

    public function index(Request $request)
    {

    }


    public function create(Request $request)
    {
        $appInfo = new AppInfo();
        $appInfo->app_name = $request->app_name;
        $appInfo->app_bundle = $request->app_bundle;
        $appInfo->fb_app_id = $request->fb_app_id;
        $appInfo->elephant_id = $request->elephant_id;
        $appInfo->elephant_secret = $request->elephant_secret;
        $appInfo->save();

        return response()->json([
           'status' => $appInfo->exists ? 200 : 205
        ]);
    }


    public function store(Request $request)
    {
        $appInfo = new AppInfo();
        $appInfo->app_name = $request->app_name;
        $appInfo->app_bundle = $request->app_bundle;
        $appInfo->fb_app_id = $request->fb_app_id;
        $appInfo->elephant_id = $request->elephant_id;
        $appInfo->elephant_secret = $request->elephant_secret;
        $appInfo->save();

        return response()->json([
            'status' => $appInfo->exists ? 200 : 205
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

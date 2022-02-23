<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppInfoController extends Controller
{

    public function index(Request $request)
    {

    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required',
            'app_bundle' => 'required',
            'fb_app_id' => 'required',
            'elephant_id' => 'required',
            'elephant_secret' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json([
               'status_code' => 400,
               'message' => $validator->messages()->first()
            ]);
        }

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


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required',
            'app_bundle' => 'required',
            'fb_app_id' => 'required',
            'elephant_id' => 'required',
            'elephant_secret' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'status_code' => 400,
                'message' => $validator->messages()->first()
            ]);
        }

        $appInfo = new AppInfo();
        $appInfo->app_name = $request->app_name;
        $appInfo->app_bundle = $request->app_bundle;
        $appInfo->fb_app_id = $request->fb_app_id;
        $appInfo->elephant_id = $request->elephant_id;
        $appInfo->elephant_secret = $request->elephant_secret;
        $appInfo->save();

        return response()->json([
            'status' => 200
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

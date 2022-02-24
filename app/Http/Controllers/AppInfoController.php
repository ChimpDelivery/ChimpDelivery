<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfoRequest;

use App\Models\AppInfo;
use App\Models\File;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppInfoController extends Controller
{

    public function index(Request $request)
    {
        return view('add-app-info-form');
    }

    public function create(AppInfoRequest $request)
    {
        $validated = $request->all();

        $appInfo = new AppInfo();
        $appInfo->app_icon = $validated['app_icon'];
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


    public function store(AppInfoRequest $request)
    {
        $inputs = $request->all();

        // generate file hash.
        $iconPath = time().'-'.$inputs['app_name'].'.'.$inputs['app_icon']->getClientOriginalExtension();
        $iconHash = md5_file($inputs['app_icon']);

        $iconHashFound = File::where('hash', $iconHash)->first();

        // icon hash not found so upload icon to public folder.
        if (!$iconHashFound)
        {
            $iconFile = new File();
            $iconFile->path = $iconPath;
            $iconFile->hash = $iconHash;
            $iconFile->save();

            $request->app_icon->move(public_path('images'), $iconPath);
        }

        // create entry.
        $appInfo = new AppInfo();
        $appInfo->app_icon = $iconPath;
        $appInfo->app_name = $inputs['app_name'];
        $appInfo->app_bundle = $inputs['app_bundle'];
        $appInfo->fb_app_id = $inputs['fb_app_id'];
        $appInfo->elephant_id = $inputs['elephant_id'];
        $appInfo->elephant_secret = $inputs['elephant_secret'];
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

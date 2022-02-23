<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfoRequest;
use App\Models\AppInfo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('list-app-info');
    }

    public function create()
    {
        return view('add-app-info-form');
    }

    public function select(Request $request)
    {
        return view('update-app-info-form')->with('id', $request->id);
    }

    public function update(AppInfoRequest $request)
    {
        $validated = $request->all();

        $appInfo = AppInfo::find($request->id);

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
}

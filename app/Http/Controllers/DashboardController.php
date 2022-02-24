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
        $inputs = $request->all();

        $appInfo = AppInfo::find($request->id);

        // upload updated icon to public folder.
        $appIconPath = time().'-'.$inputs['app_name'].'.'.$inputs['app_icon']->getClientOriginalExtension();
        $request->app_icon->move(public_path('images'), $appIconPath);

        $appInfo->app_icon = $appIconPath;
        $appInfo->app_name = $inputs['app_name'];
        $appInfo->app_bundle = $inputs['app_bundle'];
        $appInfo->fb_app_id = $inputs['fb_app_id'];
        $appInfo->elephant_id = $inputs['elephant_id'];
        $appInfo->elephant_secret = $inputs['elephant_secret'];
        $appInfo->save();

        return redirect()->route('get_app_list');
    }

    public function delete(Request $request)
    {
        $appInfo = AppInfo::find($request->id);
        $appInfo?->delete();

        return redirect()->route('get_app_list');
    }
}

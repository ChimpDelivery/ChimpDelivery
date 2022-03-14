<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfoRequest;
use App\Models\AppInfo;
use App\Models\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

use Spatie\ResponseCache\Facades\ResponseCache;

class DashboardController extends Controller
{
    public function Index(Request $request) : View
    {
        $data = [
            'appInfos' => AppInfo::paginate(10)->onEachSide(1)
        ];

        $data['appInfos']->each(function ($item) use ($request) {
            $item->latest_build_number = 1;
        });

        return view('list-app-info')->with($data);
    }

    public function CreateAppForm(Request $request) : View
    {
        $allAppInfos = app('App\Http\Controllers\AppStoreConnectController')->GetAppList($request)->getData();

        return view('add-app-info-form')->with([
            'allAppInfos' => $allAppInfos
        ]);
    }

    public function StoreAppForm(AppInfoRequest $request) : RedirectResponse
    {
        $this->PopulateAppData($request, AppInfo::withTrashed()->where('appstore_id', $request->appstore_id)->firstOrNew());
        session()->flash('success', "App: {$request->app_name} created...");
        return to_route('get_app_list');
    }

    public function SelectApp(Request $request) : View
    {
        return view('update-app-info-form')->with('appInfo', AppInfo::find($request->id));
    }

    public function UpdateApp(AppInfoRequest $request) : RedirectResponse
    {
        $this->PopulateAppData($request, AppInfo::withTrashed()->find($request->id));
        session()->flash('success', "App: {$request->app_name} updated...");
        return to_route('get_app_list');
    }

    public function BuildApp(Request $request) : RedirectResponse
    {
        session()->flash('success', "App building...");
        Artisan::call("jenkins:trigger {$request->id}");
        return to_route('get_app_list');
    }

    public function DeleteApp(Request $request) : RedirectResponse
    {
        $appInfo = AppInfo::find($request->id);
        session()->flash('success', "App: {$appInfo->app_name} deleted...");
        $appInfo?->delete();

        return to_route('get_app_list');
    }

    public function CreateBundleForm(Request $request) : View
    {
        $allAppInfos = app('App\Http\Controllers\AppStoreConnectController')->GetAppList($request)->getData();
        return view('create-bundle-form')->with('allAppInfos', $allAppInfos);
    }

    public function StoreBundleForm(Request $request) : RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'bundle_id' => array('required', 'alpha_num'),
            'bundle_name' => array('required', 'alpha_num'),
        ]);

        if ($validator->fails())
        {
            return to_route('create_bundle')
                ->withErrors($validator)
                ->withInput();
        }

        $bundleId = app('App\Http\Controllers\AppStoreConnectController')->GetBundlePrefix() . '.' . $request->bundle_id;
        $bundleList = app('App\Http\Controllers\AppStoreConnectController')->GetAllBundles($request)->getData()->bundle_ids;

        if (in_array($bundleId, $bundleList))
        {
            return to_route('create_bundle')
                ->withErrors(['bundle_id' => 'Bundle id already exists on App Store Connect!'])
                ->withInput();
        }

        app('App\Http\Controllers\AppStoreConnectController')->CreateBundle($request);

        session()->flash('success', "Bundle: com.Talus.{$request->bundle_id} created...");
        return to_route('get_app_list');
    }

    public function ClearCache() : RedirectResponse
    {
        ResponseCache::clear();
        return to_route('get_app_list');
    }

    private function GenerateHashAndUpload(string $iconPath, string $iconHash, AppInfoRequest $request) : void
    {
        $iconFile = new File();
        $iconFile->path = $iconPath;
        $iconFile->hash = $iconHash;
        $iconFile->save();

        $request->app_icon->move(public_path('images'), $iconPath);
    }

    private function PopulateAppData(AppInfoRequest $request, AppInfo $appInfo) : void
    {
        if (isset($request->app_icon))
        {
            $currentIconHash = md5_file($request->app_icon);
            $matchingHash = File::where('hash', $currentIconHash)->first();

            // icon hash not found so generate hash and upload icon file.
            if (!$matchingHash)
            {
                $iconPath = time() . '-' . $request->app_name . '.' . $request->app_icon->getClientOriginalExtension();
                $this->GenerateHashAndUpload($iconPath, $currentIconHash, $request);
            }

            $appInfo->app_icon = ($matchingHash) ? $matchingHash->path : $iconPath;
        }

        if ($appInfo->trashed())
        {
            $appInfo->restore();
        }

        // we can't update app_name, app_bundle and appstore_id in created apps.
        if (!$appInfo->exists)
        {
            $appInfo->app_name = $request->app_name;
            $appInfo->app_bundle = $request->app_bundle;
            $appInfo->appstore_id = $request->appstore_id;
        }

        $appInfo->fb_app_id = $request->fb_app_id;
        $appInfo->elephant_id = $request->elephant_id;
        $appInfo->elephant_secret = $request->elephant_secret;

        $appInfo->save();
    }
}

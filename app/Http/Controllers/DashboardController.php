<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfoRequest;
use App\Models\AppInfo;
use App\Models\File;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Spatie\ResponseCache\Facades\ResponseCache;

class DashboardController extends Controller
{
    public function Index()
    {
        return view('list-app-info')->with('appInfos', AppInfo::paginate(10));
    }

    public function CreateApp(Request $request)
    {
        $allAppInfos = app('App\Http\Controllers\AppStoreConnectController')->GetAppList($request)->getData();
        return view('add-app-info-form')->with('allAppInfos', $allAppInfos);
    }

    public function StoreApp(AppInfoRequest $request)
    {
        return $this->PopulateAppData($request, new AppInfo());
    }

    public function SelectApp(Request $request)
    {
        return view('update-app-info-form')->with('appInfo', AppInfo::find($request->id));
    }

    public function UpdateApp(AppInfoRequest $request)
    {
        return $this->PopulateAppData($request, AppInfo::find($request->id));
    }

    public function BuildApp(Request $request)
    {
        $app = AppInfo::where('id', $request->id)->first();
        if ($app)
        {
            $url = implode('', [
                env('JENKINS_HOST', 'http://localhost:8080'),
                "/job/{$app->app_name}/build?token=",
                env('JENKINS_TOKEN')
            ]);

            Http::withBasicAuth(env('JENKINS_USER'), env('JENKINS_PASS'))->get($url);
        }

        return to_route('get_app_list');
    }

    public function DeleteApp(Request $request)
    {
        $appInfo = AppInfo::find($request->id);
        $appInfo?->delete();

        return to_route('get_app_list');
    }

    public function ClearCache()
    {
        ResponseCache::clear();

        return to_route('get_app_list');
    }

    /**
     * @param string                            $iconPath
     * @param string                            $iconHash
     * @param \App\Http\Requests\AppInfoRequest $request
     *
     * @return void
     */
    private function GenerateHashAndUpload(string $iconPath, string $iconHash, AppInfoRequest $request) : void
    {
        $iconFile = new File();
        $iconFile->path = $iconPath;
        $iconFile->hash = $iconHash;
        $iconFile->save();

        $request->app_icon->move(public_path('images'), $iconPath);
    }

    /**
     * @param \App\Http\Requests\AppInfoRequest $request
     * @param \App\Models\AppInfo               $appInfo
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function PopulateAppData(AppInfoRequest $request, AppInfo $appInfo) : RedirectResponse
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
        return to_route('get_app_list');
    }
}

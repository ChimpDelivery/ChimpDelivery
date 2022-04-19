<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfoRequest;
use App\Http\Requests\StoreBundleRequest;
use App\Models\AppInfo;
use App\Models\File;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

use Spatie\ResponseCache\Facades\ResponseCache;

class DashboardController extends Controller
{
    public function Index(Request $request) : View
    {
        $data = [
            'appInfos' => AppInfo::paginate(10)->onEachSide(1),
        ];

        if (config('jenkins.enabled')) {
            $data['appInfos']->each(function ($item) use ($request) {
                $appName = $item->project_name;

                $appData = app('App\Http\Controllers\JenkinsController')->GetLatestBuildNumber($request, $appName)->getData();
                $item->latest_build_number = $appData->latest_build_number;
                $item->latest_build_url = Str::replace('http://localhost:8080', config('jenkins.host'), $appData->jenkins_url);

                $buildStatus = app('App\Http\Controllers\JenkinsController')->GetLatestBuildInfo($request, $appName, $appData->latest_build_number)->getData();
                $item->latest_build_status = $buildStatus->latest_build_status;
            });
        }

        return view('list-app-info')->with($data);
    }

    public function CreateAppForm(Request $request) : View
    {
        $allAppInfos = app('App\Http\Controllers\AppStoreConnectController')->GetAppList($request)->getData();
        $allGitProjects = app('App\Http\Controllers\GithubController')->GetRepositories()->getData();

        return view('add-app-info-form')->with([
            'allAppInfos' => $allAppInfos,
            'allGitProjects' => $allGitProjects
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

    public function UpdateApp(AppInfoRequest $request): RedirectResponse
    {
        $this->PopulateAppData($request, AppInfo::withTrashed()->find($request->id));
        session()->flash('success', "App: {$request->app_name} updated...");

        return to_route('get_app_list');
    }

    public function BuildApp(Request $request) : RedirectResponse
    {
        $appInfo = AppInfo::find($request->id);

        if ($appInfo)
        {
            Artisan::call("jenkins:trigger {$request->id} {$request->isWorkspace} {$request->tfVersion}");
            session()->flash('success', "{$appInfo->app_name} building(IS_WORKSPACE:{$request->isWorkspace}, TF_VERSION:$request->tfVersion), wait 3-4seconds then reload the page.");
        }

        return to_route('get_app_list');
    }

    public function StopJob(Request $request) : RedirectResponse
    {
        Artisan::call("jenkins:stopper {$request->projectName} {$request->buildNumber}");
        session()->flash('success', "{$request->projectName}: build {$request->buildNumber} aborted, wait 3-4 seconds then reload the page.");

        return to_route('get_app_list');
    }

    public function ScanRepo(Request $request) : RedirectResponse
    {
        Artisan::call("jenkins:scan-repo");
        session()->flash('success', "Repository scanning begins...");

        return to_route('get_app_list');
    }

    public function DeleteApp(Request $request) : RedirectResponse
    {
        $appInfo = AppInfo::find($request->id);

        if ($appInfo)
        {
            $appInfo->delete();
            session()->flash('success', "App: {$appInfo->app_name} deleted...");
        }

        return to_route('get_app_list');
    }

    public function CreateBundleForm(Request $request) : View
    {
        $allAppInfos = app('App\Http\Controllers\AppStoreConnectController')->GetAppList($request)->getData();

        return view('create-bundle-form')->with('allAppInfos', $allAppInfos);
    }

    public function StoreBundleForm(StoreBundleRequest $request) : RedirectResponse
    {
        $bundleId = config('appstore.bundle_prefix') . '.' . $request->bundle_id;
        $bundleList = app('App\Http\Controllers\AppStoreConnectController')->GetAllBundles($request)->getData()->bundle_ids;

        if (in_array($bundleId, $bundleList)) {
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
        session()->flash('success', "Cache cleared...");

        return to_route('get_app_list');
    }

    // todo: refactor mass-assignment
    private function PopulateAppData(AppInfoRequest $request, AppInfo $appInfo) : void
    {
        if ($appInfo->trashed()) {
            $appInfo->restore();
        }

        // we can't update app_name, app_bundle and appstore_id in created apps.
        if (!$appInfo->exists)
        {
            $appInfo->app_name = $request->app_name;
            $appInfo->app_bundle = $request->app_bundle;
            $appInfo->appstore_id = $request->appstore_id;
        }

        $appInfo->project_name = $request->project_name;

        if ($request->hasFile('app_icon')) {
            $appInfo->app_icon = $this->GenerateHashAndUpload($request->file('app_icon'));
        }

        $appInfo->fb_app_id = $request->fb_app_id;
        $appInfo->elephant_id = $request->elephant_id;
        $appInfo->elephant_secret = $request->elephant_secret;

        $appInfo->save();
    }

    // todo: refactor
    private function GenerateHashAndUpload($iconImage) : string
    {
        $hash = md5_file($iconImage);
        $iconFile = File::where('hash', $hash)->first();

        if (!$iconFile)
        {
            $fileName = pathinfo($iconImage->getClientOriginalName(), PATHINFO_FILENAME);

            $iconFile = new File();
            $iconFile->path = time() . "-" . $fileName . "." . $iconImage->getClientOriginalExtension();
            $iconFile->hash = md5_file($iconImage);
            $iconFile->save();

            $iconImage->move(public_path('images/app-icons'), $iconFile->path);
            return $iconFile->path;
        }

        return $iconFile->path;
    }
}

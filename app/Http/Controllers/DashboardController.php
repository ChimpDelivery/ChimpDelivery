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

use Spatie\ResponseCache\Facades\ResponseCache;

class DashboardController extends Controller
{
    public function Index(Request $request) : View
    {
        $data = [
            'appInfos' => AppInfo::orderBy('id', 'desc')
                ->paginate(10)
                ->onEachSide(1),
        ];

        $data['appInfos']->each(function ($item) use ($request)
        {
            $appData = app('App\Http\Controllers\JenkinsController')
                ->GetLatestBuildInfo($request, $item->project_name)
                ->getData();

            $item->job_exists = $appData->job_exists;

            if ($item->job_exists)
            {
                // jenkins relative data.
                $item->build_number = $appData->build_number;
                $item->build_status = $appData->build_status;
                if ($item->build_status == 'BUILDING')
                {
                    $estimatedTime = ceil($appData->timestamp / 1000) + ceil($appData->estimated_duration / 1000);
                    $estimatedTime = date('H:i:s', $estimatedTime);
                    $currentTime = date('H:i:s');
                    $item->estimated_time = ($currentTime > $estimatedTime) ? 'Unknown' : $estimatedTime;
                }
                $item->change_sets = $appData->change_sets;
                $item->jenkins_url = $appData->jenkins_url;

                // for dashboard buttons.
                $item->git_url = "https://github.com/TalusStudio/{$item->project_name}";
            }
        });

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
        $this->PopulateAppData($request, AppInfo::withTrashed()
            ->where('appstore_id', $request->appstore_id)
            ->firstOrNew()
        );

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
            $appName = $appInfo->project_name;

            $appData = app('App\Http\Controllers\JenkinsController')
                ->GetLatestBuildInfo($request, $appName)
                ->getData();

            if ($appData->build_number == 1)
            {
                Artisan::call("jenkins:default-trigger {$request->id}");
                session()->flash('success', "{$appInfo->app_name} building for first time. This build gonna be aborted by Jenkins!");
            }
            else
            {
                Artisan::call("jenkins:trigger {$request->id} master {$request->isWorkspace} {$request->tfVersion} {FALSE}");
                session()->flash('success', "{$appInfo->app_name} building(IS_WORKSPACE:{$request->isWorkspace}, TF_VERSION:$request->tfVersion), wait 3-4seconds then reload the page.");
            }
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
        $allAppInfos = app('App\Http\Controllers\AppStoreConnectController')
            ->GetAppList($request)
            ->getData();

        return view('create-bundle-form')->with('allAppInfos', $allAppInfos);
    }

    public function StoreBundleForm(StoreBundleRequest $request) : RedirectResponse
    {
        $response = app('App\Http\Controllers\AppStoreConnectController')->CreateBundle($request)->getData();
        if ($response->status->errors)
        {
            return to_route('create_bundle')
                ->withErrors(['bundle_id' => 'Bundle id already exists on App Store Connect!'])
                ->withInput();
        }

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

        $appInfo->project_name = $request->project_name;

        if ($request->hasFile('app_icon'))
        {
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

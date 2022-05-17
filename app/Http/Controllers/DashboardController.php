<?php

namespace App\Http\Controllers;

use Spatie\ResponseCache\Facades\ResponseCache;

use App\Http\Requests\AppInfoRequest;
use App\Http\Requests\StoreBundleRequest;
use App\Models\AppInfo;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    // todo: refactor
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
                    $item->build_stage = $appData->build_stage;
                }
                $item->change_sets = $appData->change_sets;
                $item->jenkins_url = $appData->jenkins_url;
            }

            // for dashboard buttons.
            $item->git_url = 'https://github.com/' . config('github.organization_name') . '/' . $item->project_name;
        });

        return view('list-app-info')->with($data);
    }

    public function CreateAppForm() : View
    {
        $allAppInfos = app('App\Http\Controllers\AppStoreConnectController')->GetAppList()->getData();
        $allGitProjects = app('App\Http\Controllers\GithubController')->GetRepositories()->getData();

        return view('add-app-info-form')->with([
            'allAppInfos' => $allAppInfos,
            'allGitProjects' => $allGitProjects
        ]);
    }

    public function StoreAppForm(AppInfoRequest $request) : RedirectResponse
    {
        $appInfoController = app('App\Http\Controllers\AppInfoController');

        $appInfoController->PopulateAppData($request, AppInfo::withTrashed()
            ->where('appstore_id', $request->appstore_id)
            ->firstOrNew()
        );

        session()->flash('success', "App: {$request->app_name} created.");

        return to_route('get_app_list');
    }

    public function SelectApp(Request $request) : View
    {
        return view('update-app-info-form')->with('appInfo', AppInfo::find($request->id));
    }

    public function UpdateApp(AppInfoRequest $request): RedirectResponse
    {
        $appInfoController = app('App\Http\Controllers\AppInfoController');

        $appInfoController->PopulateAppData($request, AppInfo::withTrashed()->find($request->id));
        session()->flash('success', "App: {$request->app_name} updated...");

        return to_route('get_app_list');
    }

    public function BuildApp(Request $request) : RedirectResponse
    {
        $appInfo = AppInfo::find($request->id);

        if ($appInfo)
        {
            $appData = app('App\Http\Controllers\JenkinsController')
                ->GetLatestBuildInfo($request, $appInfo->project_name)
                ->getData();

            if ($appData->build_number == 1 && $appData->build_status != 'ABORTED')
            {
                Artisan::call("jenkins:default-trigger {$request->id}");
                session()->flash('success', "{$appInfo->app_name} building for first time. This build gonna be aborted by Jenkins!");
            }
            else
            {
                $tfCustomVersion = isset($request->tfCustomVersion) && $request->tfCustomVersion == 'true';
                $tfCustomVersion = var_export($tfCustomVersion, true);
                $tfBuildNumber = ($tfCustomVersion == 'true') ? $request->tfBuildNumber : 0;

                Artisan::call("jenkins:trigger {$request->id} master {$request->isWorkspace} {$request->tfVersion} {FALSE} {$tfCustomVersion} {$tfBuildNumber}");

                session()->flash('success', "{$appInfo->app_name} building
                    (IS_WORKSPACE: {$request->isWorkspace},
                    TF_VERSION: {$request->tfVersion},
                    TF_CUSTOM_VERSION: {$tfCustomVersion}),
                    wait 3-4seconds then reload the page.");
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

    public function ScanRepo() : RedirectResponse
    {
        Artisan::call("jenkins:scan-repo");
        session()->flash('success', "Repository scanning begins...");

        return to_route('get_app_list');
    }

    public function DeleteApp(Request $request) : RedirectResponse
    {
        $deleteAppResponse = app('App\Http\Controllers\AppInfoController')->DeleteApp($request)->getData();
        session()->flash('success', $deleteAppResponse->message);

        return to_route('get_app_list');
    }

    public function CreateBundleForm() : View
    {
        return view('create-bundle-form');
    }

    public function StoreBundleForm(StoreBundleRequest $request) : RedirectResponse
    {
        $response = app('App\Http\Controllers\AppStoreConnectController')->CreateBundle($request)->getData();
        if (isset($response->status->errors))
        {
            return to_route('create_bundle')
                ->withErrors(['bundle_id' => $response->status->errors[0]->detail . " (Status code: {$response->status->errors[0]->status})"])
                ->withInput();
        }

        session()->flash('success', 'Bundle: ' . config('appstore.bundle_prefix') . '.' . $request->bundle_id . ' created!');
        return to_route('get_app_list');
    }

    public function ClearCache() : RedirectResponse
    {
        ResponseCache::clear();
        session()->flash('success', "Cache cleared!");

        return to_route('get_app_list');
    }
}

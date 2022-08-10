<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfo\GetAppInfoRequest;
use Spatie\ResponseCache\Facades\ResponseCache;

use App\Models\AppInfo;

use App\Http\Requests\AppInfo\StoreAppInfoRequest;
use App\Http\Requests\AppStoreConnect\StoreBundleRequest;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function Index(Request $request) : View
    {
        $data = AppInfo::orderBy('id', 'desc')->gpaginate(5)->onEachSide(1);

        $data->each(function ($project) use ($request) {
            $appData = collect(app('App\Http\Controllers\JenkinsController')
                ->GetLastBuildWithDetails($request, $project->project_name)
                ->getData());

            $this->PopulateAppDetails($project, $appData);
        });

        $currentBuildCount = $data->pluck('build_status.status')->filter(fn ($buildStatus) => $buildStatus == 'IN_PROGRESS');

        return view('list-app-info')->with([
            'appInfos' => $data,
            'currentBuildCount' => $currentBuildCount->count()
        ]);
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

    public function StoreAppForm(StoreAppInfoRequest $request) : RedirectResponse
    {
        $appInfoController = app('App\Http\Controllers\AppInfoController');
        $githubController = app('App\Http\Controllers\GithubController');

        // check repository name on org
        $gitResponse = collect($githubController->GetRepository($request->project_name)->getData());

        // git repo doesn't exit, just create it from template
        if (count($gitResponse) == 0)
        {
            $createRepoResponse = collect($githubController
                ->CreateRepository($request->project_name)
                ->getData()
            );

            // new git repo created succesfully
            if (!is_null($createRepoResponse->get('id')))
            {
                $appInfoController->PopulateAppData($request, AppInfo::withTrashed()
                    ->where('appstore_id', $request->appstore_id)
                    ->firstOrNew()
                );

                Artisan::call("jenkins:scan-repo");

                session()->flash('success', "App: {$request->app_name} created. New Git project: {$createRepoResponse->get('full_name')}");
            }
        }
        else // existing git project
        {
            $appInfoController->PopulateAppData($request, AppInfo::withTrashed()
                ->where('appstore_id', $request->appstore_id)
                ->firstOrNew()
            );

            session()->flash('success', "App: {$request->app_name} created.");
        }


        return to_route('get_app_list');
    }

    public function SelectApp(Request $request) : View
    {
        return view('update-app-info-form')->with('appInfo', AppInfo::find($request->id));
    }

    public function UpdateApp(StoreAppInfoRequest $request): RedirectResponse
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
            $job = app('App\Http\Controllers\JenkinsController')
                ->GetLastBuildSummary($request, $appInfo->project_name)
                ->getData();

            $latestBuild = $job->build_list;

            // job exists but doesn't parameterized
            if ($latestBuild->number == 1 && empty($latestBuild->url))
            {
                Artisan::call("jenkins:default-trigger {$request->id}");
                session()->flash('success', "{$appInfo->app_name} building for first time. This build gonna be aborted by Jenkins!");
            }
            else
            {
                $hasStoreCustomVersion = isset($request->storeCustomVersion) && $request->storeCustomVersion == 'true';
                $hasStoreCustomVersion = var_export($hasStoreCustomVersion, true);
                $storeBuildNumber = ($hasStoreCustomVersion == 'true') ? $request->storeBuildNumber : 0;

                Artisan::call("jenkins:trigger {$request->id} master {FALSE} {$request->platform} {$request->storeVersion} {$hasStoreCustomVersion} {$storeBuildNumber}");

                session()->flash('success', "{$appInfo->app_name} building for {$request->platform}... Wait 3-4seconds then reload the page.");
            }
        }

        return back();
    }

    public function StopJob(Request $request) : RedirectResponse
    {
        Artisan::call("jenkins:stopper {$request->projectName} {$request->buildNumber}");
        session()->flash('success', "{$request->projectName}: build {$request->buildNumber} aborted, wait 3-4 seconds then reload the page.");

        return back();
    }

    public function ScanRepo() : RedirectResponse
    {
        Artisan::call("jenkins:scan-repo");
        session()->flash('success', "Repository scanning begins...");

        return back();
    }

    public function DeleteApp(GetAppInfoRequest $request) : RedirectResponse
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

    // cache system disabled for now
    public function ClearCache() : RedirectResponse
    {
        ResponseCache::clear();
        session()->flash('success', 'Cache cleared!');

        return back();
    }

    private function PopulateAppDetails($project, mixed $appData) : void
    {
        // always populate git url data
        $project->git_url = 'https://github.com/' . config('github.organization_name') . '/' . $project->project_name;

        // if job exist on jenkins, populate project build data
        if (!$appData->get('job_exists')) { return; }

        // copy params from jenkins job
        $appData->map(function ($item, $key) use (&$project) {
            $project->setAttribute($key, $item);
        });

        if ($project->build_status->status == 'IN_PROGRESS')
        {
            $project->estimated_time = $this->CalculateBuildFinishDate($appData);
        }
    }

    private function CalculateBuildFinishDate(mixed $appData) : string
    {
        $estimatedTime = ceil($appData->get('timestamp') / 1000) + ceil($appData->get('estimated_duration') / 1000);
        $estimatedTime = date('H:i:s', $estimatedTime);
        $currentTime = date('H:i:s');

        return ($currentTime > $estimatedTime) ? 'Unknown' : $estimatedTime;
    }
}

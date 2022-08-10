<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfo\GetAppInfoRequest;
use Spatie\ResponseCache\Facades\ResponseCache;

use App\Models\AppInfo;

use App\Http\Requests\Dashboard\SelectAppRequest;
use App\Http\Requests\AppInfo\StoreAppInfoRequest;
use App\Http\Requests\AppStoreConnect\StoreBundleRequest;

use App\Http\Requests\Jenkins\BuildRequest;
use App\Http\Requests\Jenkins\StopJobRequest;

use Illuminate\Contracts\View\View;

use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function Index() : View
    {
        $data = AppInfo::orderBy('id', 'desc')->paginate(5)->onEachSide(1);

        $data->each(function ($project) {
            $jenkinsResponse = collect(app('App\Http\Controllers\JenkinsController')
                ->GetLastBuildWithDetails($project)
                ->getData()
            );

            $this->PopulateAppDetails($project, $jenkinsResponse);
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
        $gitResponse = collect($githubController->GetRepository($request->validated('project_name'))->getData());

        // git repo doesn't exit, just create it from template
        if (count($gitResponse) == 0)
        {
            $createRepoResponse = collect($githubController
                ->CreateRepository($request->validated('project_name'))
                ->getData()
            );

            // new git repo created succesfully
            if (!is_null($createRepoResponse->get('id')))
            {
                $appInfoController->PopulateAppData($request, AppInfo::withTrashed()
                    ->where('appstore_id', $request->validated('appstore_id'))
                    ->firstOrNew()
                );

                Artisan::call("jenkins:scan-repo");

                session()->flash('success', "App: {$request->validated('app_name')} created. New Git project: {$createRepoResponse->get('full_name')}");
            }
        }
        else // existing git project
        {
            $appInfoController->PopulateAppData($request, AppInfo::withTrashed()
                ->where('appstore_id', $request->validated('appstore_id'))
                ->firstOrNew()
            );

            session()->flash('success', "App: {$request->validated('app_name')} created.");
        }


        return to_route('get_app_list');
    }

    public function SelectApp(SelectAppRequest $request) : View
    {
        return view('update-app-info-form')->with('appInfo', AppInfo::find($request->validated('id')));
    }

    public function UpdateApp(StoreAppInfoRequest $request): RedirectResponse
    {
        $appInfoController = app('App\Http\Controllers\AppInfoController');

        $appInfoController->PopulateAppData($request, AppInfo::withTrashed()->find($request->validated('id')));
        session()->flash('success', "App: {$request->validated('app_name')} updated...");

        return to_route('get_app_list');
    }

    public function BuildApp(BuildRequest $request) : RedirectResponse
    {
        session()->flash('success', app('App\Http\Controllers\JenkinsController')->BuildJob($request)->getData()->status);

        return back();
    }

    public function StopJob(StopJobRequest $request) : RedirectResponse
    {
        $projectName = $request->validated('project_name');
        $buildNumber = $request->validated('build_number');

        $stopJobResponse = app('App\Http\Controllers\JenkinsController')->StopJob($request)->getData();
        $flashMessage = ($stopJobResponse->status == 200)
            ? "{$projectName}: {$buildNumber} aborted!"
            : "{$projectName}: {$buildNumber} can not aborted!";
        session()->flash('success', $flashMessage);

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
            $error = $response->status->errors[0];

            return to_route('create_bundle')
                ->withErrors([ 'bundle_id' => $error->detail . " (Status code: {$error->status})" ])
                ->withInput();
        }

        session()->flash('success', 'Bundle: ' . config('appstore.bundle_prefix') . '.' . $request->validated('bundle_id') . ' created!');
        return to_route('get_app_list');
    }

    private function PopulateAppDetails(AppInfo $project, mixed $jenkinsData) : void
    {
        // always populate git url data
        $project->git_url = 'https://github.com/' . config('github.organization_name') . '/' . $project->project_name;

        // if job exist on jenkins, populate project build data
        if (!$jenkinsData->get('job_exists')) { return; }

        // copy params from jenkins job
        $jenkinsData->map(function ($item, $key) use (&$project) {
            $project->setAttribute($key, $item);
        });

        // if job has no build, there is no build_status property (and other jenkins data)
        if (!isset($project->build_status)) { return; }

        if ($project->build_status->status == 'IN_PROGRESS')
        {
            $project->estimated_time = $this->CalculateBuildFinishDate($jenkinsData);
        }
    }

    private function CalculateBuildFinishDate(mixed $jenkinsData) : string
    {
        $estimatedTime = ceil($jenkinsData->get('timestamp') / 1000) + ceil($jenkinsData->get('estimated_duration') / 1000);
        $estimatedTime = date('H:i:s', $estimatedTime);
        $currentTime = date('H:i:s');

        return ($currentTime > $estimatedTime) ? 'Unknown' : $estimatedTime;
    }

    // cache system disabled for now
    public function ClearCache() : RedirectResponse
    {
        ResponseCache::clear();
        session()->flash('success', 'Cache cleared!');

        return back();
    }
}

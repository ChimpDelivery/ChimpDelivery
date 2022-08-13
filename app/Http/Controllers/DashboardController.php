<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;

use App\Http\Requests\AppInfo\GetAppInfoRequest;
use App\Http\Requests\AppInfo\StoreAppInfoRequest;
use App\Http\Requests\AppInfo\UpdateAppInfoRequest;

use App\Http\Requests\AppStoreConnect\StoreBundleRequest;

use App\Http\Requests\Jenkins\BuildRequest;
use App\Http\Requests\Jenkins\StopJobRequest;

use Illuminate\Contracts\View\View;

use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function Index() : View
    {
        $data = AppInfo::orderBy('id', 'desc')->paginate(5)->onEachSide(1);
        $data->each(function (AppInfo $app)
        {
            $request = GetAppInfoRequest::createFromGlobals();
            $request = $request->merge(['id' => $app->id]);

            $jenkinsResponse = collect(app('App\Http\Controllers\JenkinsController')
                ->GetLastBuildWithDetails($request)
                ->getData()
            );

            $this->PopulateAppDetails($app, $jenkinsResponse);
        });

        $currentBuildCount = $data->pluck('build_status.status')->filter(fn ($buildStatus) => $buildStatus == 'IN_PROGRESS');

        return view('list-app-info')->with([
            'appInfos' => $data,
            'currentBuildCount' => $currentBuildCount->count()
        ]);
    }

    public function CreateAppForm() : View
    {
        $allAppInfos = app(AppStoreConnectController::class)->GetAppList()->getData();
        $allGitProjects = app(GithubController::class)->GetRepositories()->getData();

        return view('add-app-info-form')->with([
            'allAppInfos' => $allAppInfos,
            'allGitProjects' => $allGitProjects->response
        ]);
    }

    public function StoreAppForm(StoreAppInfoRequest $request) : RedirectResponse
    {
        $appInfoController = app(AppInfoController::class);
        $githubController = app(GithubController::class);

        // check repository name on org
        $gitResponse = $githubController->GetRepository($request)->getData();

        // git repo doesn't exit, just create it from template
        if ($gitResponse->status == Response::HTTP_NOT_FOUND)
        {
            $createRepoResponse = $githubController->CreateRepository($request)->getData();

            // new git repo created succesfully
            if ($createRepoResponse->status == Response::HTTP_OK)
            {
                $appInfoController->PopulateAppData($request, AppInfo::withTrashed()
                    ->where('appstore_id', $request->validated('appstore_id'))
                    ->firstOrNew()
                );

                Artisan::call("jenkins:scan-repo");

                session()->flash('success', "App: {$request->validated('app_name')} created. New Git project: {$createRepoResponse->response->full_name}");
            }
            else
            {
                session()->flash('success', "App: {$request->validated('app_name')} created but Git project can not created! Delete app from dashboard and try again.");
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

    public function SelectApp(GetAppInfoRequest $request) : View
    {
        return view('update-app-info-form')->with('appInfo', AppInfo::find($request->validated('id')));
    }

    public function UpdateApp(UpdateAppInfoRequest $request): RedirectResponse
    {
        $appInfoController = app(AppInfoController::class);
        $response = $appInfoController->UpdateApp($request);

        session()->flash('success', "App: {$response->getData()->app_name} updated...");

        return to_route('get_app_list');
    }

    public function BuildApp(BuildRequest $request) : RedirectResponse
    {
        session()->flash('success', app(JenkinsController::class)->BuildJob($request)->getData()->status);

        return back();
    }

    public function StopJob(StopJobRequest $request) : RedirectResponse
    {
        $app = AppInfo::find($request->validated('id'));
        $buildNumber = $request->validated('build_number');

        $stopJobResponse = app(JenkinsController::class)->StopJob($request)->getData();
        $flashMessage = ($stopJobResponse->status == 200)
            ? "{$app->project_name}: {$buildNumber} aborted!"
            : "{$app->project_name}: {$buildNumber} can not aborted!";
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
        $deleteAppResponse = app(AppInfoController::class)->DeleteApp($request)->getData();
        session()->flash('success', $deleteAppResponse->message);

        return to_route('get_app_list');
    }

    public function CreateBundleForm() : View
    {
        return view('create-bundle-form');
    }

    public function StoreBundleForm(StoreBundleRequest $request) : RedirectResponse
    {
        $response = app(AppStoreConnectController::class)->CreateBundle($request)->getData();
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

    private function PopulateAppDetails(AppInfo $app, mixed $jenkinsData) : void
    {
        // always populate git url data
        $app->git_url = 'https://github.com/' . config('github.organization_name') . '/' . $app->project_name;

        // copy params from jenkins job
        $jenkinsData->map(function ($item, $key) use (&$app)
        {
            $app->setAttribute($key, $item);
        });

        // if job exist on jenkins, populate project build data
        if (!$jenkinsData->get('job_exists')) { return; }

        // if job has no build, there is no build_status property (and other jenkins data)
        if (!isset($app->build_status)) { return; }

        if ($app->build_status->status == 'IN_PROGRESS')
        {
            $app->estimated_time = $this->CalculateBuildFinishDate($jenkinsData->get('timestamp'), $jenkinsData->get('estimated_duration'));
        }
    }

    private function CalculateBuildFinishDate($timestamp, $estimatedDuration) : string
    {
        $estimatedTime = ceil($timestamp / 1000) + ceil($estimatedDuration / 1000);
        $estimatedTime = date('H:i:s', $estimatedTime);
        $currentTime = date('H:i:s');

        return ($currentTime > $estimatedTime) ? 'Unknown' : $estimatedTime;
    }
}

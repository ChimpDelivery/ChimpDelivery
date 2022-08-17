<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;

use App\Http\Requests\AppInfo\GetAppInfoRequest;
use App\Http\Requests\AppInfo\StoreAppInfoRequest;
use App\Http\Requests\AppInfo\UpdateAppInfoRequest;

use App\Http\Requests\AppStoreConnect\StoreBundleRequest;

use App\Http\Requests\Jenkins\BuildRequest;
use App\Http\Requests\Jenkins\StopJobRequest;

use App\Http\Requests\Workspace\StoreWsSettingsRequest;

use Illuminate\Contracts\View\View;

use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function Index() : View
    {
        $wsApps = AppInfo::where('workspace_id', '=', Auth::user()->workspace->id);

        $paginatedApps = $wsApps->orderBy('id', 'desc')
            ->paginate(5)
            ->onEachSide(1);

        $paginatedApps->each(function (AppInfo $app) {

            $request = GetAppInfoRequest::createFromGlobals();
            $request = $request->merge(['id' => $app->id]);

            $jenkinsResponse = app(JenkinsController::class)->GetJobLastBuild($request)->getData();
            $this->PopulateBuildDetails($app, $jenkinsResponse);
        });

        $currentBuildCount = $paginatedApps->pluck('build_status.status')->filter(fn ($buildStatus) => $buildStatus == 'IN_PROGRESS');

        return view('list-app-info')->with([
            'totalAppCount' => $wsApps->count(),
            'appInfos' => $paginatedApps,
            'currentBuildCount' => $currentBuildCount->count()
        ]);
    }

    public function GetWsSettings() : View
    {
        $workspace = Auth::user()->workspace;

        $this->authorize('view', $workspace);

        return view('workspace-settings-form')->with([
            'workspace_id' => $workspace->id,
        ]);
    }

    public function StoreWsSettings(StoreWsSettingsRequest $request) : RedirectResponse
    {
        app(WorkspaceController::class)->UpdateWorkspace($request);

        return to_route('workspace_settings');
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
        $createAppResponse = app(AppInfoController::class)->CreateApp($request)->getData();
        $projectName = $createAppResponse->app->project_name;

        Artisan::call("jenkins:scan-repo");

        $flashMessage = match($createAppResponse->git->status)
        {
            Response::HTTP_OK => "Project: <b>{$projectName}</b> created as new Git Project.", // new git project
            Response::HTTP_UNPROCESSABLE_ENTITY => "Project: <b>{$projectName}</b> created.", // git project already exist
            Response::HTTP_NOT_FOUND => "Error: Git project couldn't created! Make sure there is an valid template project on Github Organization.",
            default => "Git Status: {$createAppResponse->git->status}",
        };
        session()->flash('success', $flashMessage);

        return to_route('get_app_list');
    }

    public function SelectApp(GetAppInfoRequest $request) : View
    {
        return view('update-app-info-form')->with('appInfo', AppInfo::find($request->validated('id')));
    }

    public function UpdateApp(UpdateAppInfoRequest $request): RedirectResponse
    {
        $response = app(AppInfoController::class)->UpdateApp($request);
        session()->flash('success', "Project: <b>{$response->getData()->project_name}</b> updated.");

        return to_route('get_app_list');
    }

    public function DeleteApp(GetAppInfoRequest $request) : RedirectResponse
    {
        session()->flash('success', app(AppInfoController::class)->DeleteApp($request)->getData()->message);

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
        $flashMessage = ($stopJobResponse->status == Response::HTTP_OK)
            ? "Project: <b>{$app->project_name}</b> Build: <b>{$buildNumber}</b> aborted!"
            : "Project: <b>{$app->project_name}</b> Build: <b>{$buildNumber}</b> can not aborted!";
        session()->flash('success', $flashMessage);

        return back();
    }

    public function ScanRepo() : RedirectResponse
    {
        Artisan::call("jenkins:scan-repo");
        session()->flash('success', "Repository scanning begins...");

        return back();
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

        session()->flash('success', 'Bundle: <b>' . config('appstore.bundle_prefix') . '.' . $request->validated('bundle_id') . '</b> created!');
        return to_route('get_app_list');
    }

    private function PopulateBuildDetails(AppInfo $app, mixed $jenkinsResponse) : void
    {
        $app->git_url = 'https://github.com/' . config('github.organization_name') . '/' . $app->project_name;

        $app->jenkins_status = $jenkinsResponse->jenkins_status;
        $app->jenkins_data = $jenkinsResponse->jenkins_data;

        if ($app?->jenkins_data?->status == 'IN_PROGRESS')
        {
            $app->jenkins_data->estimated_time = $this->GetBuildFinish(
                $app->jenkins_data->startTimeMillis,
                $app->jenkins_data->estimated_duration
            );
        }
    }

    private function GetBuildFinish($timestamp, $estimatedDuration) : string
    {
        $estimatedTime = ceil($timestamp / 1000) + ceil($estimatedDuration / 1000);
        $estimatedTime = date('H:i:s', $estimatedTime);
        $currentTime = date('H:i:s');

        return ($currentTime > $estimatedTime) ? 'Unknown' : $estimatedTime;
    }
}

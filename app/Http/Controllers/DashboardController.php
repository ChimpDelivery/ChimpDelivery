<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\AppInfoController;
use App\Http\Controllers\Api\AppStoreConnectController;
use App\Http\Controllers\Api\GithubController;
use App\Http\Controllers\Api\JenkinsController;
use App\Http\Controllers\Api\WorkspaceController;

use App\Http\Requests\AppInfo\GetAppInfoRequest;
use App\Http\Requests\AppInfo\UpdateAppInfoRequest;

use App\Http\Requests\Jenkins\BuildRequest;
use App\Http\Requests\Workspace\JoinWorkspaceRequest;
use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

use App\Models\AppInfo;

use Illuminate\Contracts\View\View;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function Index() : View
    {
        $isWorkspaceUser = Auth::user()->workspace->id !== 1;

        if ($isWorkspaceUser)
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

        return view('workspace-settings')->with([ 'isNew' => true ]);
    }

    public function StoreWorkspaceForm(StoreWorkspaceSettingsRequest $request) : RedirectResponse
    {
        $response = app(WorkspaceController::class)->StoreOrUpdate($request)->getData();
        $flashMessage = "Workspace: <b>{$response->response->name}</b> " . (($response->wasRecentlyCreated) ? 'created.' : 'updated.');
        session()->flash('success', $flashMessage);

        return to_route('workspace_settings');
    }

    public function GetWorkspaceForm() : View
    {
        $workspace = app(WorkspaceController::class)->Get();

        return view('workspace-settings')->with([
            'workspace' => $workspace,
            'isNew' => false,
        ]);
    }

    public function GetJoinWorkspaceForm() : View
    {
        return view('workspace-join');
    }

    public function PostJoinWorkspaceForm(JoinWorkspaceRequest $request) : RedirectResponse
    {
        $workspace = app(WorkspaceController::class)->JoinWorkspace($request);

        return to_route('index');
    }

    public function CreateAppForm() : View
    {
        $allAppInfos = app(AppStoreConnectController::class)->GetAppList()->getData();
        $allGitProjects = app(GithubController::class)->GetRepositories()->getData();

        $isBadCredentials = $allGitProjects->status == Response::HTTP_UNAUTHORIZED;

        return view('appinfo-form')->with([
            'all_appstore_apps' => $allAppInfos,
            'github_auth_failed' => $isBadCredentials,
            'github_projects' => ($allGitProjects->status == Response::HTTP_UNAUTHORIZED) ? collect() : $allGitProjects->response
        ]);
    }

    public function SelectApp(GetAppInfoRequest $request) : View
    {
        $app = AppInfo::find($request->validated('id'));
        $this->authorize('view', $app);

        return view('appinfo-form')->with('appInfo', $app);
    }

    public function UpdateApp(UpdateAppInfoRequest $request): RedirectResponse
    {
        $this->authorize('update', AppInfo::find($request->validated('id')));

        $response = app(AppInfoController::class)->UpdateApp($request);
        session()->flash('success', "Project: <b>{$response->getData()->project_name}</b> updated.");

        return to_route('index');
    }

    public function DeleteApp(GetAppInfoRequest $request) : RedirectResponse
    {
        $this->authorize('delete', AppInfo::find($request->validated('id')));

        session()->flash('success', app(AppInfoController::class)->DeleteApp($request)->getData()->message);
        return to_route('index');
    }

    public function BuildApp(BuildRequest $request) : RedirectResponse
    {
        $this->authorize('build', AppInfo::find($request->validated('id')));

        session()->flash('success', app(JenkinsController::class)->BuildJob($request)->getData()->status);
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

    private function PopulateBuildDetails(AppInfo $app, mixed $jenkinsResponse) : void
    {
        $app->git_url = 'https://github.com/' . Auth::user()->workspace->githubSetting->organization_name . '/' . $app->project_name;

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

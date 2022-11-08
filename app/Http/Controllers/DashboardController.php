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

use App\Models\AppInfo;

use Illuminate\Contracts\View\View;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
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
}

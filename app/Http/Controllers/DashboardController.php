<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\JenkinsController;
use App\Http\Controllers\Api\WorkspaceController;

use App\Http\Requests\Jenkins\BuildRequest;
use App\Http\Requests\Workspace\JoinWorkspaceRequest;

use App\Models\AppInfo;

use Illuminate\Contracts\View\View;

use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function GetJoinWorkspaceForm() : View
    {
        return view('workspace-join');
    }

    public function PostJoinWorkspaceForm(JoinWorkspaceRequest $request) : RedirectResponse
    {
        $workspace = app(WorkspaceController::class)->JoinWorkspace($request);

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

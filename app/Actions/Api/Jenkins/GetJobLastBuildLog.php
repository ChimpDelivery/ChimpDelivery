<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Models\Workspace;
use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJobLastBuildLog
{
    use AsAction;

    private AppInfo $app;

    public function handle(GetAppInfoRequest $request) : JsonResponse
    {
        $response = app(JenkinsService::class)->GetResponse("/job/{$this->app->project_name}/job/master/lastBuild/consoleText");
        return response()->json($response->jenkins_data);
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        $this->app = AppInfo::find($request->validated('id'));

        $userWorkspaceId = Auth::user()->workspace->id;
        $appWorkspaceId = $this->app->workspace->id;

        return $appWorkspaceId === $userWorkspaceId
            && $userWorkspaceId !== Workspace::$DEFAULT_WORKSPACE_ID;
    }
}

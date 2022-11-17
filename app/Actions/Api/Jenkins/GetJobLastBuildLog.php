<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Models\Workspace;
use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJobLastBuildLog
{
    use AsAction;

    private AppInfo $app;
    private string $lastBuildFullLog;

    public function handle(GetAppInfoRequest $request) : mixed
    {
        $response = app(JenkinsService::class)
            ->GetResponse("/job/{$this->app->project_name}/job/master/lastBuild/consoleText", true);

        $this->lastBuildFullLog = $response->jenkins_data;

        return $response;
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        $this->app = AppInfo::find($request->validated('id'));

        $userWorkspaceId = Auth::user()->workspace->id;
        $appWorkspaceId = $this->app->workspace->id;

        return $appWorkspaceId === $userWorkspaceId
            && $userWorkspaceId !== Workspace::$DEFAULT_WORKSPACE_ID
            && Auth::user()->can('view job log');
    }

    public function htmlResponse(mixed $response) : View
    {
        return view('build-log')->with([
            'app' => $this->app,
            'full_log' => $this->lastBuildFullLog,
        ]);
    }

    public function jsonResponse(mixed $response) : JsonResponse
    {
        return response()->json($response->jenkins_data);
    }
}

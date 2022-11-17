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

    public function handle(GetAppInfoRequest $request) : string
    {
        $response = app(JenkinsService::class)
            ->GetResponse("/job/{$this->app->project_name}/job/master/lastBuild/consoleText", true);

        return $response->jenkins_data;
    }

    public function htmlResponse(string $response) : View
    {
        return view('build-log')->with([
            'app' => $this->app,
            'full_log' => $response,
        ]);
    }

    public function jsonResponse(string $response) : JsonResponse
    {
        return response()->json($response);
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        $this->app = AppInfo::find($request->validated('id'));

        $userWorkspaceId = Auth::user()->workspace->id;
        $appWorkspaceId = $this->app->workspace->id;

        return $appWorkspaceId === $userWorkspaceId
            && !Auth::user()->isNew()
            && Auth::user()->can('view job log');
    }
}

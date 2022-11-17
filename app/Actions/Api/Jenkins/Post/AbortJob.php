<?php

namespace App\Actions\Api\Jenkins\Post;

use App\Models\Workspace;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\Jenkins\StopJobRequest;
use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

class AbortJob extends BaseJenkinsAction
{
    private AppInfo $app;

    public function handle(StopJobRequest $request) : array
    {
        $buildNumber = $request->validated('build_number');

        $url = "/job/{$this->app->project_name}/job/master/{$buildNumber}/stop";
        $response =  app(JenkinsService::class)->PostResponse($url);
        $responseCode = $response->jenkins_status;

        $isResponseSucceed = $responseCode == Response::HTTP_OK;
        $responseMessage = ($isResponseSucceed)
            ? "<b>{$this->app->project_name}</b>, Build: <b>{$buildNumber}</b> aborted!"
            : "{$this->app->project_name}, Build: {$buildNumber} could not aborted! Error Code: {$responseCode}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }

    public function authorize(StopJobRequest $request) : bool
    {
        $this->app = AppInfo::find($request->validated('id'));

        $userWorkspaceId = Auth::user()->workspace->id;
        $appWorkspaceId = $this->app->workspace->id;

        return $appWorkspaceId === $userWorkspaceId
            && $userWorkspaceId !== Workspace::$DEFAULT_WS_ID
            && Auth::user()->can('delete app');
    }
}

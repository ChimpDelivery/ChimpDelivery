<?php

namespace App\Actions\Api\Jenkins\Post;

use Illuminate\Http\Response;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\Jenkins\BuildRequest;
use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

class ParameterizeJob extends BaseJenkinsAction
{
    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) { }

    public function handle(BuildRequest $request) : array
    {
        $app = AppInfo::find($request->validated('id'));

        $response = $this->jenkinsService->PostResponse("/job/{$app->project_name}/job/master/build?delay=0sec");
        $responseCode = $response->jenkins_status;

        $isResponseSucceed = $responseCode == Response::HTTP_CREATED;
        $responseMessage = ($isResponseSucceed)
            ? "Project: <b>{$app->project_name}</b> is parameterizing. This build gonna be <b>aborted</b> by Jenkins!"
            : "Error Code: {$responseCode}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }
}

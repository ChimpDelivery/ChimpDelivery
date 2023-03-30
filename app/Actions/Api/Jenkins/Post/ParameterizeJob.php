<?php

namespace App\Actions\Api\Jenkins\Post;

use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

use Illuminate\Http\Response;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\Jenkins\BuildRequest;

class ParameterizeJob extends BaseJenkinsAction
{
    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) {
    }

    public function handle(AppInfo $appInfo) : array
    {
        $response = $this->jenkinsService->PostResponse("/job/{$appInfo->project_name}/job/master/build?delay=0sec");
        $responseCode = $response->jenkins_status;

        $isResponseSucceed = $responseCode === Response::HTTP_CREATED;
        $responseMessage = ($isResponseSucceed)
            ? "Project: <b>{$appInfo->project_name}</b> is parameterizing. This build gonna be <b>aborted</b> by Jenkins!"
            : "Error Code: {$responseCode}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }

    public function asController(BuildRequest $request) : array
    {
        return $this->handle(
            $request->user()->workspace->apps()->findOrFail($request->validated('id'))
        );
    }
}

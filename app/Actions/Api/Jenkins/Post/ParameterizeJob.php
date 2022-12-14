<?php

namespace App\Actions\Api\Jenkins\Post;

use Illuminate\Http\Response;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\Jenkins\BuildRequest;
use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

class ParameterizeJob extends BaseJenkinsAction
{
    public function handle(BuildRequest $request, JenkinsService $service) : array
    {
        $app = AppInfo::find($request->validated('id'));

        $response = $service->PostResponse("/job/{$app->project_name}/job/master/build?delay=0sec");
        $responseCode = $response->jenkins_status;

        $isResponseSucceed = $responseCode == Response::HTTP_CREATED;
        $responseMessage = ($isResponseSucceed)
            ? "Project: {$app->project_name} is parameterizing. This build gonna be <b>aborted</b> by Jenkins!"
            : "Error Code: {$responseCode}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }
}

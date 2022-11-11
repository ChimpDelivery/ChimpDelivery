<?php

namespace App\Actions\Api\Jenkins\Post;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Traits\AsActionResponse;
use App\Http\Requests\Jenkins\BuildRequest;

class ParameterizeJob
{
    use AsAction;
    use AsActionResponse;

    public function handle(BuildRequest $request) : array
    {
        $app = AppInfo::find($request->validated('id'));

        $service = new JenkinsService($request);
        $response = $service->PostResponse("/job/{$app->project_name}/job/master/build?delay=0sec");
        $responseCode = $response->jenkins_status;

        $isResponseSucceed = $responseCode == Response::HTTP_CREATED;
        $responseMessage = ($isResponseSucceed)
            ? "Project: {$app->project_name} is parameterizing. This build gonna be aborted by Jenkins!"
            : "Error Code: {$responseCode}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }
}

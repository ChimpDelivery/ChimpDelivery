<?php

namespace App\Actions\Api\Jenkins\Post;

use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

use Laravel\Pennant\Feature;

use Illuminate\Http\Response;

use App\Models\AppInfo;
use App\Features\AppBuild;
use App\Services\JenkinsService;
use App\Http\Requests\Jenkins\StopJobRequest;

class AbortJob extends BaseJenkinsAction
{
    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) {
    }

    public function handle(AppInfo $appInfo, array $inputs) : array
    {
        $buildNumber = $inputs['build_number'];

        $url = "/job/{$appInfo->project_name}/job/master/{$buildNumber}/stop";
        $response =  $this->jenkinsService->PostResponse($url);
        $responseCode = $response->jenkins_status;

        $isResponseSucceed = $responseCode === Response::HTTP_OK;
        $responseMessage = ($isResponseSucceed)
            ? "<b>{$appInfo->project_name}</b>, Build: <b>{$buildNumber}</b> aborted!"
            : "{$appInfo->project_name}, Build: {$buildNumber} could not aborted! Error Code: {$responseCode}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }

    public function asController(StopJobRequest $request) : array
    {
        return $this->handle(
            $request->user()->workspace->apps()->findOrFail($request->validated('id')),
            $request->safe()->all()
        );
    }

    public function authorize(StopJobRequest $request) : bool
    {
        return $request->user()->can('abort job')&& Feature::active(AppBuild::class);
    }
}

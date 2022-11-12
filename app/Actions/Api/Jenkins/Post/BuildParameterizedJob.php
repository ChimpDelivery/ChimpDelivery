<?php

namespace App\Actions\Api\Jenkins\Post;

use Illuminate\Http\Response;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\Jenkins\BuildRequest;
use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

class BuildParameterizedJob extends BaseJenkinsAction
{
    private string $branch = "master";

    public function handle(BuildRequest $request) : array
    {
        $validated = $request->validated();
        $validated['store_custom_version'] ??= 'false';
        $validated['store_build_number'] = ($validated['store_custom_version'] == 'true')
            ? ($validated['store_build_number'] ?? 1)
            : 0;

        $app = AppInfo::find($validated['id']);

        $service = new JenkinsService($request);
        $url = "/job/{$app->project_name}/job/{$this->branch}/buildWithParameters"
            ."?INVOKE_PARAMETERS=false"
            ."&PLATFORM={$validated['platform']}"
            ."&APP_ID={$validated['id']}"
            ."&STORE_BUILD_VERSION={$validated['store_version']}"
            ."&STORE_CUSTOM_BUNDLE_VERSION={$validated['store_custom_version']}"
            ."&STORE_BUNDLE_VERSION={$validated['store_build_number']}";

        $response = $service->PostResponse($url);
        $responseCode = $response->jenkins_status;

        $isResponseSucceed = $responseCode == Response::HTTP_CREATED;
        $responseMessage = ($isResponseSucceed)
            ? "{$app->project_name}, building for {$validated['platform']}..."
            : "Error Code: {$responseCode}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }
}

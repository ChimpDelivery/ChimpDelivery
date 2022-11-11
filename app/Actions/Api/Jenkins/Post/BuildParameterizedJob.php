<?php

namespace App\Actions\Api\Jenkins\Post;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\Jenkins\BuildRequest;

class BuildParameterizedJob
{
    use AsAction;

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

    public function htmlResponse(array $response) : RedirectResponse
    {
        if ($response['success'])
        {
            return back()->with('success', $response['message']);
        }

        return back()->withErrors($response['message']);
    }

    public function jsonResponse(array $response) : JsonResponse
    {
        return response()->json($response);
    }
}

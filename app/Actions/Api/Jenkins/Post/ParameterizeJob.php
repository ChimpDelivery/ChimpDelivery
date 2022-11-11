<?php

namespace App\Actions\Api\Jenkins\Post;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\Jenkins\BuildRequest;

class ParameterizeJob
{
    use AsAction;

    public function handle(BuildRequest $request) : RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        $app = AppInfo::find($validated['id']);

        $service = new JenkinsService($request);
        $response = $service->PostResponse("/job/{$app->project_name}/job/master/build?delay=0sec");
        $responseCode = $response->jenkins_status;
        $isResponseSucceed = $responseCode == Response::HTTP_CREATED;
        $responseMessage = ($isResponseSucceed)
            ? "Project: {$app->project_name} is parameterizing. This build gonna be aborted by Jenkins!"
            : "Error Code: {$responseCode}";

        if ($request->expectsJson())
        {
            return response()->json([
                'status' => $responseMessage
            ]);
        }

        if ($isResponseSucceed)
        {
            return back()->with('success', $responseMessage);
        }

        return back()->withErrors($responseMessage);
    }
}

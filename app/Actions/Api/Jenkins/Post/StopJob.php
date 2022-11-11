<?php

namespace App\Actions\Api\Jenkins\Post;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\Jenkins\StopJobRequest;

class StopJob
{
    use AsAction;

    public function handle(StopJobRequest $request) : RedirectResponse|JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));
        $buildNumber = $request->validated('build_number');

        $service = new JenkinsService($request);
        $url = "/job/{$app->project_name}/job/master/{$buildNumber}/stop";
        $response =  $service->PostResponse($url);
        $responseCode = $response->jenkins_status;
        $isResponseSucceed = $responseCode == Response::HTTP_OK;

        $flashMessage = ($isResponseSucceed)
            ? "<b>{$app->project_name}</b>, Build: <b>{$buildNumber}</b> aborted!"
            : "{$app->project_name}, Build: {$buildNumber} could not aborted! Error Code: {$responseCode}";

        if ($request->expectsJson())
        {
            return response()->json([
                'status' => $responseCode
            ]);
        }

        if ($isResponseSucceed)
        {
            return back()->with('success', $flashMessage);
        }

        return back()->withErrors($flashMessage);
    }
}

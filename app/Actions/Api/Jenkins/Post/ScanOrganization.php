<?php

namespace App\Actions\Api\Jenkins\Post;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Services\JenkinsService;

class ScanOrganization
{
    use AsAction;

    public function handle(Request $request) : RedirectResponse|JsonResponse
    {
        $service = new JenkinsService($request);
        $response = $service->PostResponse("/build?delay=0");
        $isResponseSucceed = $response->jenkins_status == Response::HTTP_OK;

        $responseMessage = ($isResponseSucceed)
            ? 'Repository scanning begins...'
            : "Repository scanning could not run! Error Code: {$response->jenkins_status}";

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

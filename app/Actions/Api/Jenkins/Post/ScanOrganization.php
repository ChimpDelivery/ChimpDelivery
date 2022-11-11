<?php

namespace App\Actions\Api\Jenkins\Post;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Services\JenkinsService;
use App\Traits\AsActionResponse;

class ScanOrganization
{
    use AsAction;
    use AsActionResponse;

    public function handle(Request $request) : array
    {
        $service = new JenkinsService($request);
        $response = $service->PostResponse("/build?delay=0");

        $isResponseSucceed = $response->jenkins_status == Response::HTTP_OK;
        $responseMessage = ($isResponseSucceed)
            ? 'Repository scanning begins.'
            : "Repository scanning could not run! Error Code: {$response->jenkins_status}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }

    public function authorize(Request $request) : bool
    {
        return $request->expectsJson()
            ? true
            : Auth::user()->can('scan jobs');
    }
}

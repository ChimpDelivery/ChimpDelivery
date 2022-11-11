<?php

namespace App\Actions\Api\Jenkins\Post;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Services\JenkinsService;

class ScanOrganization
{
    use AsAction;

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

    public function authorize(Request $request) : bool
    {
        return $request->expectsJson()
            ? true
            : Auth::user()->can('scan jobs');
    }
}

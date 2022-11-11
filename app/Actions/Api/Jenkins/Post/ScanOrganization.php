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

    private readonly bool $isResponseSucceed;
    private string $responseMessage;

    public function handle(Request $request) : Request
    {
        $service = new JenkinsService($request);
        $response = $service->PostResponse("/build?delay=0");

        $this->isResponseSucceed = $response->jenkins_status == Response::HTTP_OK;
        $this->responseMessage = ($this->isResponseSucceed)
            ? 'Repository scanning begins.'
            : "Repository scanning could not run! Error Code: {$response->jenkins_status}";

        return $request;
    }

    public function htmlResponse(Request $request) : RedirectResponse
    {
        if ($this->isResponseSucceed)
        {
            return back()->with('success', $this->responseMessage);
        }

        return back()->withErrors($this->responseMessage);
    }

    public function jsonResponse(Request $request) : JsonResponse
    {
        return response()->json([
            'status' => $this->responseMessage
        ]);
    }

    public function authorize(Request $request) : bool
    {
        return $request->expectsJson()
            ? true
            : Auth::user()->can('scan jobs');
    }
}

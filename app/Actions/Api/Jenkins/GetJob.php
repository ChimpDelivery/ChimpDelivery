<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJob
{
    use AsAction;

    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) {
    }

    public function handle(GetAppInfoRequest $request) : JsonResponse
    {
        $app = $request->user()->workspace->apps()->findOrFail($request->validated('id'));

        $response = $this->jenkinsService->GetResponse("/job/{$app->project_name}/api/json");
        $response->jenkins_data = collect($response->jenkins_data)->only(['name', 'url']);

        return response()->json($response);
    }
}

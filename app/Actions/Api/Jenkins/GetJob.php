<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJob
{
    use AsAction;

    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) {
    }

    public function handle(AppInfo $appInfo) : JsonResponse
    {
        $response = $this->jenkinsService->GetResponse("/job/{$appInfo->project_name}/api/json");
        $response->jenkins_data = collect($response->jenkins_data)->only([ 'name', 'url' ]);

        return response()->json($response);
    }

    public function asController(GetAppInfoRequest $request) : JsonResponse
    {
        return $this->handle(
            $request->user()->workspace->apps()->findOrFail($request->validated('id'))
        );
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        return $request->user()->can('view apps');
    }
}

<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Models\AppInfo;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

use App\Services\JenkinsService;

class GetJob
{
    use AsAction;

    public function handle(GetAppInfoRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));

        $service = new JenkinsService($request);

        $jobResponse = $service->GetResponse("/job/{$app->project_name}/api/json");
        $jobResponse->jenkins_data = collect($jobResponse->jenkins_data)->only(['name', 'url']);

        return response()->json($jobResponse);
    }
}

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

    public function handle(GetAppInfoRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));

        $jobResponse = app(JenkinsService::class)->GetResponse("/job/{$app->project_name}/api/json");
        $jobResponse->jenkins_data = collect($jobResponse->jenkins_data)->only(['name', 'url']);

        return response()->json($jobResponse);
    }
}

<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJob
{
    use AsAction;

    public function handle(GetAppInfoRequest $request, JenkinsService $service) : JsonResponse
    {
        $app = Auth::user()->workspace->apps()->findOrFail($request->validated('id'));

        $response = $service->GetResponse("/job/{$app->project_name}/api/json");
        $response->jenkins_data = collect($response->jenkins_data)->only(['name', 'url']);

        return response()->json($response);
    }
}

<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJob
{
    use AsAction;

    private AppInfo $app;

    public function handle(GetAppInfoRequest $request) : JsonResponse
    {
        $response = app(JenkinsService::class)->GetResponse("/job/{$this->app->project_name}/api/json");
        $response->jenkins_data = collect($response->jenkins_data)->only(['name', 'url']);

        return response()->json($response);
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        $this->app = Auth::user()->workspace->apps()->findOrFail($request->validated('id'));

        return !Auth::user()->isNew();
    }
}

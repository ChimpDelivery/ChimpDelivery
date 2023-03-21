<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJobLastBuildLog
{
    use AsAction;

    private AppInfo $app;

    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) { }

    public function handle() : string
    {
        $response = $this->jenkinsService->GetResponse("/job/{$this->app->project_name}/job/master/lastBuild/consoleText", true);

        return $response->jenkins_data ?? '';
    }

    public function htmlResponse(string $response) : View
    {
        return view('build-log')->with([
            'app' => $this->app,
            'full_log' => $response,
        ]);
    }

    public function jsonResponse(string $response) : JsonResponse
    {
        return response()->json($response);
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        $this->app = $request->user()->workspace->apps()->findOrFail($request->validated('id'));

        return $request->user()->can('view job log');
    }
}

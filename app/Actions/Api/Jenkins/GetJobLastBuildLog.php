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

    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) {
    }

    public function handle(AppInfo $appInfo) : array
    {
        $response = $this->jenkinsService->GetResponse("/job/{$appInfo->project_name}/job/master/lastBuild/consoleText", true);

        return [
            'app' => $appInfo,
            'full_log' => $response->jenkins_data ?? ''
        ];
    }

    public function asController(GetAppInfoRequest $request) : array
    {
        return $this->handle(
            $request->user()->workspace->apps()->findOrFail($request->validated('id'))
        );
    }

    public function htmlResponse(array $response) : View
    {
        return view('build-log')->with($response);
    }

    public function jsonResponse(array $response) : JsonResponse
    {
        return response()->json($response);
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        return $request->user()->can('view job log');
    }
}

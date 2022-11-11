<?php

namespace App\Actions\Api\Jenkins\Post;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Actions\Api\Jenkins\GetJobBuilds;
use App\Http\Requests\Jenkins\BuildRequest;

class BuildJob
{
    use AsAction;

    public function handle(BuildRequest $request) : array
    {
        $jobBuilds = GetJobBuilds::run($request)->getData();
        $firstBuild = $jobBuilds->jenkins_data[0];

        // Job exist but there are no builds.
        // Jenkins jobs created as non-parameterized by default.
        // We need to handle this step with minimal build.
        if ($firstBuild->number == 1 && empty($firstBuild->url))
        {
            return ParameterizeJob::run($request);
        }

        return BuildParameterizedJob::run($request);
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

    public function authorize(BuildRequest $request) : bool
    {
        $service = new JenkinsService($request);

        return $request->expectsJson()
            ? $service->GetTargetWorkspaceId() === AppInfo::find($request->validated('id'))->workspace_id
            : Auth::user()->can('build job') && $service->GetTargetWorkspaceId() === AppInfo::find($request->validated('id'))->workspace_id;
    }
}

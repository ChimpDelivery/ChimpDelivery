<?php

namespace App\Actions\Api\Jenkins\Post;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Traits\AsActionResponse;
use App\Actions\Api\Jenkins\GetJobBuilds;
use App\Http\Requests\Jenkins\BuildRequest;

class BuildJob
{
    use AsAction;
    use AsActionResponse;

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

    public function authorize(BuildRequest $request) : bool
    {
        $service = new JenkinsService($request);

        return $request->expectsJson()
            ? $service->GetTargetWorkspaceId() === AppInfo::find($request->validated('id'))->workspace_id
            : Auth::user()->can('build job') && $service->GetTargetWorkspaceId() === AppInfo::find($request->validated('id'))->workspace_id;
    }
}

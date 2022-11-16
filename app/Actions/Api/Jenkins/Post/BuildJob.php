<?php

namespace App\Actions\Api\Jenkins\Post;

use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Actions\Api\Jenkins\GetJobBuilds;
use App\Http\Requests\Jenkins\BuildRequest;
use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

class BuildJob extends BaseJenkinsAction
{
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
        $app = AppInfo::find($request->validated('id'));

        $userWorkspaceId = Auth::user()->workspace->id;
        $appWorkspaceId = $app->workspace->id;

        return $appWorkspaceId == $userWorkspaceId
            && $userWorkspaceId !== Workspace::$DEFAULT_WORKSPACE_ID
            && Auth::user()->can('build job');
    }
}

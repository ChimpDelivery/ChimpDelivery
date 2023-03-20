<?php

namespace App\Actions\Api\Jenkins\Post;

use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

use Illuminate\Support\Facades\Auth;

use App\Actions\Api\Jenkins\GetJobBuilds;
use App\Http\Requests\Jenkins\BuildRequest;
use App\Jobs\Jenkins\BuildParameterizedJob;

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

        $app = Auth::user()->workspace->apps()->find($request->validated('id'));
        BuildParameterizedJob::dispatch($app, $request->safe()->all(), Auth::user());

        return [
            'success' => true,
            'message' => "<b>{$app->project_name}</b>, building for <b>{$request->validated('platform')}</b>...",
        ];
    }

    public function authorize(BuildRequest $request) : bool
    {
        return Auth::user()->can('build job');
    }
}

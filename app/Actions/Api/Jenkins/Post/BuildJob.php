<?php

namespace App\Actions\Api\Jenkins\Post;

use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

use Laravel\Pennant\Feature;

use App\Actions\Api\Jenkins\GetJobBuilds;
use App\Jobs\Jenkins\BuildParameterizedJob;
use App\Http\Requests\Jenkins\BuildRequest;

use App\Models\User;
use App\Models\AppInfo;
use App\Features\AppBuild;

class BuildJob extends BaseJenkinsAction
{
    public function handle(AppInfo $appInfo, array $inputs, User $user) : array
    {
        $jobBuilds = GetJobBuilds::run($appInfo)->getData();
        $firstBuild = $jobBuilds->jenkins_data[0];

        // Job exist but there are no builds.
        // Jenkins jobs created as non-parameterized by default.
        // We need to handle this step with minimal build.
        if ($firstBuild->number === 1 && empty($firstBuild->url))
        {
            return ParameterizeJob::run($appInfo);
        }

        BuildParameterizedJob::dispatch($appInfo, $inputs, $user);

        return [
            'success' => true,
            'message' => "<b>{$appInfo->project_name}</b>, building for <b>{$inputs['platform']}</b>...",
        ];
    }

    public function asController(BuildRequest $request) : array
    {
        return $this->handle(
            $request->user()->workspace->apps()->findOrFail($request->validated('id')),
            $request->safe()->all(),
            $request->user()
        );
    }

    public function authorize(BuildRequest $request) : bool
    {
        return $request->user()->can('build job') && Feature::for($request->user())->active(AppBuild::class);
    }
}

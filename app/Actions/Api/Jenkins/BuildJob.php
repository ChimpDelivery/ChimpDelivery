<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\Jenkins\BuildRequest;
use App\Actions\Api\Jenkins\Post\ParameterizeJob;
use App\Actions\Api\Jenkins\Post\BuildParameterizedJob;

class BuildJob
{
    use AsAction;

    public function handle(BuildRequest $request) : RedirectResponse|JsonResponse
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
}

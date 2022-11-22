<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJobBuilds
{
    use AsAction;

    private AppInfo $app;

    public function handle(GetAppInfoRequest $request) : JsonResponse
    {
        $jobResponse = app(JenkinsService::class)->GetResponse("/job/{$this->app->project_name}/job/master/api/json");
        $builds = collect($jobResponse->jenkins_data?->builds);

        // add nextBuildNumber value to build list for detailed info for job parametrization.
        if (count($builds) == 0)
        {
            $builds = $builds->push(
                collect([
                    '_class' => 'org.jenkinsci.plugins.workflow.job.WorkflowRu',
                    'number' => $jobResponse->jenkins_data->nextBuildNumber,
                    'url' => ''
                ])
            );
        }

        $jobResponse->jenkins_data = $builds;

        return response()->json($jobResponse);
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        $this->app = Auth::user()->workspace->apps()->findOrFail($request->validated('id'));

        return !Auth::user()->isNew();
    }
}

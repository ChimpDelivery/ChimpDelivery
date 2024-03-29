<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJobBuilds
{
    use AsAction;

    // jenkins api filters
    private array $filters = [
        'job_parameters' => 'url,nextBuildNumber,builds[url,number]{0,3}',
    ];

    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) {
    }

    public function handle(AppInfo $appInfo) : JsonResponse
    {
        $jobResponse = $this->jenkinsService->GetResponse($this->CreateUrl($appInfo));
        $builds = collect($jobResponse->jenkins_data?->builds);

        // add nextBuildNumber value to build list for detailed info for job parametrization.
        if (count($builds) === 0)
        {
            $builds = $builds->push(
                collect([
                    '_class' => 'org.jenkinsci.plugins.workflow.job.WorkflowRu',
                    'number' => $jobResponse->jenkins_data?->nextBuildNumber,
                    'url' => '',
                ])
            );
        }

        $jobResponse->jenkins_data = $builds;

        return response()->json($jobResponse);
    }

    public function asController(GetAppInfoRequest $request) : JsonResponse
    {
        return $this->handle(
            $request->user()->workspace->apps()->findOrFail($request->validated('id'))
        );
    }

    private function CreateUrl(AppInfo $app) : string
    {
        return implode('/', [
            "/job/{$app->project_name}/job",
            'master',
            "api/json?tree={$this->filters['job_parameters']}",
        ]);
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        return $request->user()->can('view apps');
    }
}

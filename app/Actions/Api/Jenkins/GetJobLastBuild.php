<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJobLastBuild
{
    use AsAction;

    // jenkins api filters
    private array $filters = [
        'job_parameters' => 'actions[*[name,value]]{0}',
        'job_changesets' => 'changeSets[*[id,msg,authorEmail]{0,5}]',
    ];

    public function handle(?GetAppInfoRequest $request, ?AppInfo $appInfo = null) : JsonResponse
    {
        $app = $appInfo ?? Auth::user()->workspace->apps()->findOrFail($request->validated('id'));
        $jenkinsService = app(JenkinsService::class);

        // find last build of job
        $jobResponse = $jenkinsService->GetResponse($this->CreateJobUrl($app->project_name));
        $builds = collect($jobResponse->jenkins_data);
        $lastBuild = $builds->first();

        if ($lastBuild)
        {
            $lastBuildApiUrl = $this->CreateLastBuildUrl($app->project_name, $lastBuild->id);
            $lastBuildDetails = $jenkinsService->GetResponse($lastBuildApiUrl);

            $lastBuild->build_platform = $this->GetBuildPlatform($lastBuildDetails->jenkins_data);
            $lastBuild->change_sets = $this->GetCommitHistory($lastBuildDetails->jenkins_data);
            $lastBuild->stop_details = $this->GetStopDetail($lastBuild);

            // if job is running, calculate average duration
            if ($lastBuild->status == JobStatus::IN_PROGRESS->value)
            {
                $lastBuild->estimated_duration = $builds->avg('durationMillis');
            }
        }

        $jobResponse->jenkins_data = $lastBuild;

        return response()->json($jobResponse);
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        return !Auth::user()->isNew();
    }

    private function CreateLastBuildUrl(string $projectName, int $lastBuildId) : string
    {
        return implode('/', [
            "/job/{$projectName}/job",
            'master',
            $lastBuildId,
            "api/json?tree={$this->filters['job_parameters']},{$this->filters['job_changesets']}",
        ]);
    }

    private function CreateJobUrl(string $projectName) : string
    {
        return implode('/', [
            "/job/{$projectName}/job",
            'master',
            'wfapi/runs'
        ]);
    }

    private function GetBuildPlatform(mixed $rawJenkinsResponse) : string
    {
        // parameters[1] === Platform parameter in Jenkinsfile
        // todo: refactor
        return $rawJenkinsResponse->actions[0]?->parameters[1]?->value ?? 'Appstore';
    }

    private function GetCommitHistory(mixed $rawJenkinsResponse) : Collection
    {
        return collect($rawJenkinsResponse->changeSets[0]->items ?? [])
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'msg' => $item->msg,
                        'authorEmail'=> $item->authorEmail
                    ];
                })->reverse()->values();
    }

    private function GetStopDetail(mixed $lastBuild) : Collection
    {
        $buildStages = collect($lastBuild->stages);

        $stopStages = $buildStages->whereIn('status', JobStatus::GetErrorStages());
        $stopStage = $stopStages?->first()?->name ?? $buildStages->last()?->name;
        $stopStageDetail = $stopStages?->first()?->error?->message ?? '';

        return collect([
            'stage' => $stopStage,
            'output' => $stopStageDetail,
        ]);
    }
}

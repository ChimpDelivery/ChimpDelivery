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
        'job_changesets' => 'changeSets[*[id,authorEmail,comment]{0,5}]',
    ];

    public function handle(?GetAppInfoRequest $request, ?AppInfo $appInfo = null) : JsonResponse
    {
        $app = $appInfo ?? Auth::user()->workspace->apps()->findOrFail($request->validated('id'));
        $jenkinsService = app(JenkinsService::class);

        // find last build of job
        $jobResponse = $jenkinsService->GetResponse($this->CreateJobUrl($app));
        $builds = collect($jobResponse->jenkins_data);
        $lastBuild = $builds->first();

        if ($lastBuild)
        {
            $lastBuildApiUrl = $this->CreateLastBuildUrl($app, $lastBuild->id);
            $lastBuildDetails = $jenkinsService->GetResponse($lastBuildApiUrl);

            $lastBuild->build_platform = $this->GetBuildPlatform($lastBuildDetails->jenkins_data);
            $lastBuild->change_sets = $this->GetCommitHistory($lastBuildDetails->jenkins_data, $app);
            $lastBuild->stop_details = $this->GetStopDetail($lastBuild);

            $buildStatus = JobStatus::tryFrom($lastBuild->status) ?? JobStatus::NOT_IMPLEMENTED;

            // check job in queue
            $buildInQueue = JobStatus::IN_PROGRESS && count(collect($lastBuild->stages)) == 0;
            if ($buildInQueue)
            {
                $buildStatus = JobStatus::QUEUED;
                $lastBuild->status = JobStatus::QUEUED->value;
            }

            // if job is running, calculate average duration
            if ($buildStatus == JobStatus::IN_PROGRESS)
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

    private function CreateLastBuildUrl(AppInfo $app, int $lastBuildId) : string
    {
        return implode('/', [
            "/job/{$app->project_name}/job",
            'master',
            $lastBuildId,
            "api/json?tree={$this->filters['job_parameters']},{$this->filters['job_changesets']}",
        ]);
    }

    private function CreateJobUrl(AppInfo $app) : string
    {
        return implode('/', [
            "/job/{$app->project_name}/job",
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

    private function GetCommitHistory(mixed $rawJenkinsResponse, AppInfo $app) : Collection
    {
        return collect($rawJenkinsResponse->changeSets[0]->items ?? [])
                ->map(function ($commit) use ($app)
                {
                    return [
                        'id' => $commit->id,
                        'url' => $this->GetCommitLink($commit, $app),
                        'comment' => $commit->comment,
                        'authorEmail'=> $commit->authorEmail
                    ];
                })->reverse()->values();
    }

    private function GetCommitLink($commit, AppInfo $app) : string
    {
        $isInternalCommit = $commit->authorEmail === 'noreply@github.com';

        $orgName = Auth::user()->orgName();

        return $isInternalCommit
            ? '#'
            : "https://github.com/{$orgName}/{$app->project_name}/commit/{$commit->id}";
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

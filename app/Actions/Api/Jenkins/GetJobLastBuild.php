<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJobLastBuild
{
    use AsAction;

    // jenkins api filters
    private array $filters = [
        'job_parameters' => 'actions[*[name,value]]{0}',
        'job_changesets' => 'changeSets[*[id,authorEmail,comment]]',
    ];

    private AppInfo $app;

    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) { }

    public function handle(?GetAppInfoRequest $request, ?AppInfo $appInfo = null) : JsonResponse
    {
        $this->app = $appInfo ?? $request->user()->workspace->apps()->findOrFail($request->validated('id'));

        // find last build of job
        $jobResponse = $this->jenkinsService->GetResponse($this->CreateJobUrl());
        $builds = collect($jobResponse->jenkins_data);

        // last build returned as a first item in collection from jenkins api
        $build = $builds->first();

        if ($build)
        {
            $build->status = $this->GetStatus($build);

            // if job is running, calculate average duration
            if ($build->status == JobStatus::IN_PROGRESS)
            {
                $build->estimated_duration = $builds->avg('durationMillis');
            }

            // populate build details with another request
            $buildDetails = $this->jenkinsService->GetResponse($this->CreateLastBuildUrl($build->id));
            $build->build_platform = $this->GetBuildPlatform($buildDetails->jenkins_data);

            $build->commit = $this->GetLastCommit($buildDetails->jenkins_data);
            $build->stop_details = $this->GetStopDetail($build);
        }

        $jobResponse->jenkins_data = $build;

        return response()->json($jobResponse);
    }

    private function CreateLastBuildUrl(int $lastBuildId) : string
    {
        return implode('/', [
            "/job/{$this->app->project_name}/job",
            'master',
            $lastBuildId,
            "api/json?tree={$this->filters['job_parameters']},{$this->filters['job_changesets']}",
        ]);
    }

    private function CreateJobUrl() : string
    {
        return implode('/', [
            "/job/{$this->app->project_name}/job",
            'master',
            'wfapi/runs'
        ]);
    }

    private function GetBuildPlatform(\stdClass $rawJenkinsResponse) : string
    {
        // parameters[1] === Platform parameter in Jenkinsfile
        // todo: refactor
        return $rawJenkinsResponse->actions[0]?->parameters[1]?->value ?? JobPlatform::Appstore->value;
    }

    private function GetLastCommit(\stdClass $rawJenkinsResponse) : null|array
    {
        return collect($rawJenkinsResponse->changeSets[0]->items ?? [])
            ->map(function ($commit) {
                return [
                    'id' => $commit->id,
                    'url' => $this->GetCommitLink($commit),
                    'comment' => $commit->comment,
                    'authorEmail'=> $commit->authorEmail,
                ];
            })->reverse()->first();
    }

    // get related commit link in commit history
    private function GetCommitLink(\stdClass $commit) : string
    {
        $projectName = $this->app->project_name;
        $orgName = $this->app->workspace->githubOrgName();

        return $commit->authorEmail === 'noreply@github.com'
            ? "https://github.com/TalusStudio/TalusWebBackend-JenkinsDSL/commit/{$commit->id}"
            : "https://github.com/{$orgName}/{$projectName}/commit/{$commit->id}";
    }

    private function GetStopDetail(\stdClass $lastBuild) : Collection
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

    private function GetStatus(\stdClass $lastBuild) : JobStatus
    {
        $inProgress = $lastBuild->status === JobStatus::IN_PROGRESS->value;
        $stageCount = count(collect($lastBuild->stages));

        // queued and running jobs have same status (IN_PROGRESS)
        // lets make the distinction
        if ($inProgress && $stageCount == 0)
        {
            return JobStatus::IN_QUEUE;
        }

        return JobStatus::tryFrom($lastBuild->status) ?? JobStatus::NOT_IMPLEMENTED;
    }
}

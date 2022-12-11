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

    public function handle(?GetAppInfoRequest $request, ?AppInfo $appInfo = null) : JsonResponse
    {
        $app = $appInfo ?? Auth::user()->workspace->apps()->findOrFail($request->validated('id'));

        // find last build of job
        $jobApiUrl = "/job/{$app->project_name}/job/master/wfapi/runs";
        $jobResponse = app(JenkinsService::class)->GetResponse($jobApiUrl);

        $builds = collect($jobResponse->jenkins_data);
        $lastBuild = $builds->first();

        if ($lastBuild)
        {
            $lastBuildApiUrl = "/job/{$app->project_name}/job/master/{$lastBuild->id}/api/json?tree=actions,changeSets[*[id,msg,authorEmail]]";
            $lastBuildDetails = app(JenkinsService::class)->GetResponse($lastBuildApiUrl);

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

    private function GetBuildPlatform(mixed $rawJenkinsResponse) : string
    {
        return $rawJenkinsResponse->actions[0]?->parameters[1]?->value ?? 'Appstore';
    }

    private function GetCommitHistory(mixed $rawJenkinsResponse) : Collection
    {
        return isset($rawJenkinsResponse->changeSets[0])
            ? collect($rawJenkinsResponse->changeSets[0]->items)->pluck('msg')->reverse()->take(5)->values()
            : collect();
    }

    private function GetStopDetail(mixed $lastBuild) : Collection
    {
        $buildStages = collect($lastBuild->stages);

        // find stopped stage
        $buildStopStage = $buildStages->whereIn('status', [
            JobStatus::FAILED->value,
            JobStatus::ABORTED->value,
        ])?->first()?->name ?? $buildStages->last()?->name;

        // find stage error msg
        $buildStopStageDetail = $buildStages->whereIn('status', [
            JobStatus::FAILED->value,
            JobStatus::ABORTED->value,
        ])?->first()?->error?->message ?? '';

        return collect([
            'stage' => $buildStopStage,
            'output' => $buildStopStageDetail,
        ]);
    }
}

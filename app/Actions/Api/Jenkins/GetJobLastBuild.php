<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetJobLastBuild
{
    use AsAction;

    public function handle(GetAppInfoRequest $request) : JsonResponse
    {
        $app = Auth::user()->workspace->apps()->findOrFail($request->id);

        $jobResponse = app(JenkinsService::class)
            ->GetResponse("/job/{$app->project_name}/job/master/wfapi/runs");

        $builds = collect($jobResponse->jenkins_data);
        $lastBuild = $builds->first();

        if ($lastBuild)
        {
            $lastBuildDetails = app(JenkinsService::class)
                ->GetResponse("/job/{$app->project_name}/job/master/{$lastBuild->id}/api/json");

            // platform
            $jobHasParameters = isset($lastBuildDetails->jenkins_data->actions[0]->parameters);
            $buildPlatform = ($jobHasParameters)
                ? $lastBuildDetails->jenkins_data->actions[0]?->parameters[1]?->value
                : 'Appstore';
            $lastBuild->build_platform = $buildPlatform;

            // add commit history
            $changeSets = isset($lastBuildDetails->jenkins_data->changeSets[0])
                ? collect($lastBuildDetails->jenkins_data->changeSets[0]->items)
                    ->pluck('msg')
                    ->reverse()
                    ->take(5)
                    ->values()
                : collect();
            $lastBuild->change_sets = $changeSets;

            // if job is running, calculate average duration
            if ($lastBuild->status == 'IN_PROGRESS')
            {
                $lastBuild->estimated_duration = $builds->avg('durationMillis');
            }

            // add job build detail
            $jobStages = collect($lastBuild->stages);

            $jobStage = $jobStages->whereIn('status', ['FAILED', 'ABORTED'])?->first()?->name ?? $jobStages->last()?->name;
            $jobStageDetail = $jobStages->whereIn('status', ['FAILED', 'ABORTED'])?->first()?->error?->message ?? '';

            $lastBuild->stop_details =  collect([
                'stage' => $jobStage,
                'output' => $jobStageDetail
            ]);
        }

        $jobResponse->jenkins_data = $lastBuild;

        return response()->json($jobResponse);
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        return !Auth::user()->isNew();
    }
}

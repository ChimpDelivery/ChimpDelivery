<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Actions\Api\Jenkins\JobStatus;
use App\Actions\Api\Jenkins\GetJobLastBuild;

class GetWorkspaceIndex
{
    use AsAction;

    public function handle() : View
    {
        $paginatedApps = Auth::user()->workspace->apps()
            ->orderBy('id', 'desc')
            ->paginate(5)
            ->onEachSide(1);

        $paginatedApps->each(function (AppInfo $app) {
            $this->InjectJenkinsDetails(
                $app,
                GetJobLastBuild::run(null, $app)
            );
        });

        return view('list-workspace-apps')->with([
            'totalAppCount' => $paginatedApps->total(),
            'appInfos' => $paginatedApps,
        ]);
    }

    private function InjectJenkinsDetails(AppInfo $app, JsonResponse $jenkinsResponse) : void
    {
        $response = $jenkinsResponse->getData();

        //
        $jenkinsData = $response->jenkins_data;
        if ($jenkinsData?->status == JobStatus::IN_PROGRESS->value)
        {
            $jenkinsData->estimated_time = $this->GetBuildFinish(
                $jenkinsData->startTimeMillis,
                $jenkinsData->estimated_duration
            );
        }

        //
        $app->jenkins_status = $response->jenkins_status;
        $app->jenkins_data = $jenkinsData;
    }

    private function GetBuildFinish($timestamp, $estimatedDuration) : string
    {
        $estimatedTime = ceil($timestamp / 1000) + ceil($estimatedDuration / 1000);
        $estimatedTime = date('H:i:s', $estimatedTime);
        $currentTime = date('H:i:s');

        return ($currentTime > $estimatedTime) ? 'Unknown' : $estimatedTime;
    }
}

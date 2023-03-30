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

        $paginatedApps->each(function (AppInfo $appInfo) {
            $this->InjectJenkinsDetails(
                $appInfo,
                GetJobLastBuild::run($appInfo)
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
        if ($jenkinsData?->status === JobStatus::IN_PROGRESS->value)
        {
            $jenkinsData->estimated_time = $this->GetBuildFinishDate(
                $jenkinsData->startTimeMillis,
                $jenkinsData->estimated_duration
            );
        }

        //
        $app->jenkins_status = $response->jenkins_status;
        $app->jenkins_data = $jenkinsData;
    }

    private function GetBuildFinishDate(float|int $start, float|int $duration) : string
    {
        $currentDate = date('H:i:s');

        // convert jenkins time
        $estimatedDate = ceil($start / 1000) + ceil($duration / 1000);
        $estimatedDate = date('H:i:s', $estimatedDate);

        return ($currentDate > $estimatedDate) ? 'Unknown' : $estimatedDate;
    }
}

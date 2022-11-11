<?php

namespace App\Actions\Dashboard;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

use App\Models\AppInfo;
use App\Http\Requests\AppInfo\GetAppInfoRequest;
use App\Actions\Api\Jenkins\GetJobLastBuild;

class GetIndexForm
{
    use AsAction;

    public function handle() : View
    {
        $isWorkspaceUser = Auth::user()->workspace->id !== 1;
        if (!$isWorkspaceUser)
        {
            return view('workspace-settings')->with([
                'isNew' => true
            ]);
        }

        $workspaceApps = Auth::user()->workspace->apps();

        $paginatedApps = $workspaceApps->orderBy('id', 'desc')
            ->paginate(5)
            ->onEachSide(1);

        $paginatedApps->each(function (AppInfo $app) {
            $request = GetAppInfoRequest::createFromGlobals();
            $request = $request->merge(['id' => $app->id]);

            $jenkinsResponse = GetJobLastBuild::run($request)->getData();
            $this->PopulateBuildDetails($app, $jenkinsResponse);
        });

        $currentBuildCount = $paginatedApps->pluck('build_status.status')
            ->filter(fn($buildStatus) => $buildStatus == 'IN_PROGRESS');

        return view('list-app-info')->with([
            'totalAppCount' => $workspaceApps->count(),
            'appInfos' => $paginatedApps,
            'currentBuildCount' => $currentBuildCount->count()
        ]);
    }

    private function PopulateBuildDetails(AppInfo $app, mixed $jenkinsResponse) : void
    {
        $app->git_url = 'https://github.com/' . Auth::user()->workspace->githubSetting->organization_name . '/' . $app->project_name;

        $app->jenkins_status = $jenkinsResponse->jenkins_status;
        $app->jenkins_data = $jenkinsResponse->jenkins_data;

        if ($app?->jenkins_data?->status == 'IN_PROGRESS') {
            $app->jenkins_data->estimated_time = $this->GetBuildFinish(
                $app->jenkins_data->startTimeMillis,
                $app->jenkins_data->estimated_duration
            );
        }
    }

    private function GetBuildFinish($timestamp, $estimatedDuration) : string
    {
        $estimatedTime = ceil($timestamp / 1000) + ceil($estimatedDuration / 1000);
        $estimatedTime = date('H:i:s', $estimatedTime);
        $currentTime = date('H:i:s');

        return ($currentTime > $estimatedTime) ? 'Unknown' : $estimatedTime;
    }
}

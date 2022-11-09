<?php

namespace App\Actions\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

use App\Http\Requests\Jenkins\StopJobRequest;

use App\Models\AppInfo;

use App\Http\Controllers\Api\JenkinsController;

class StopJob
{
    use AsAction;

    public function handle(StopJobRequest $request) : RedirectResponse
    {
        $app = AppInfo::find($request->validated('id'));

        $buildNumber = $request->validated('build_number');

        $stopJobResponse = app(JenkinsController::class)->StopJob($request)->getData();
        $flashMessage = ($stopJobResponse->status == Response::HTTP_OK)
            ? "Project: <b>{$app->project_name}</b> Build: <b>{$buildNumber}</b> aborted!"
            : "Project: <b>{$app->project_name}</b> Build: <b>{$buildNumber}</b> can not aborted!";

        return back()->with('success', $flashMessage);
    }
}

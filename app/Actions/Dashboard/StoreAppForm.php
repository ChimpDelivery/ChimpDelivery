<?php

namespace App\Actions\Dashboard;

use App\Http\Controllers\Api\AppInfoController;
use App\Http\Requests\AppInfo\StoreAppInfoRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreAppForm
{
    use AsAction;

    public function handle(StoreAppInfoRequest $request) : RedirectResponse
    {
        $createAppResponse = app(AppInfoController::class)->CreateApp($request)->getData();
        $projectName = $createAppResponse->app->project_name;

        Artisan::call("jenkins:scan-repo");

        $flashMessage = match($createAppResponse->git->status)
        {
            Response::HTTP_OK => "Project: <b>{$projectName}</b> created as new Git Project.", // new git project
            Response::HTTP_UNPROCESSABLE_ENTITY => "Project: <b>{$projectName}</b> created.", // git project already exist
            Response::HTTP_NOT_FOUND => "Error: Git project couldn't created! Make sure there is an valid template project on Github Organization.",
            default => "Git Status: {$createAppResponse->git->status}",
        };
        session()->flash('success', $flashMessage);

        return to_route('index');
    }

}

<?php

namespace App\Actions\Dashboard;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

use App\Http\Controllers\Api\AppStoreConnectController;
use App\Http\Controllers\Api\GithubController;

class CreateAppForm
{
    use AsAction;

    public function handle() : View
    {
        $allAppInfos = app(AppStoreConnectController::class)->GetAppList()->getData();
        $allGitProjects = app(GithubController::class)->GetRepositories()->getData();

        $isBadCredentials = $allGitProjects->status == Response::HTTP_UNAUTHORIZED;

        return view('appinfo-form')->with([
            'all_appstore_apps' => $allAppInfos,
            'github_auth_failed' => $isBadCredentials,
            'github_projects' => ($allGitProjects->status == Response::HTTP_UNAUTHORIZED) ? collect() : $allGitProjects->response
        ]);
    }
}

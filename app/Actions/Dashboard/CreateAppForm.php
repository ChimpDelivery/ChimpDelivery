<?php

namespace App\Actions\Dashboard;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;

use App\Actions\Api\Github\GetRepositories;

use App\Http\Controllers\Api\AppStoreConnectController;

class CreateAppForm
{
    use AsAction;

    public function handle(Request $request) : View
    {
        $allAppInfos = app(AppStoreConnectController::class)->GetAppList()->getData();
        $allGitProjects = GetRepositories::run($request)->getData();

        $isBadCredentials = $allGitProjects->status == Response::HTTP_UNAUTHORIZED;

        return view('appinfo-form')->with([
            'all_appstore_apps' => $allAppInfos,
            'github_auth_failed' => $isBadCredentials,
            'github_projects' => ($isBadCredentials)
                ? collect()
                : $allGitProjects->response
        ]);
    }
}

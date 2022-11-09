<?php

namespace App\Actions\Dashboard;

use App\Actions\Api\AppStoreConnect\GetAppList;
use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;

use App\Actions\Api\Github\GetRepositories;

class CreateAppForm
{
    use AsAction;

    public function handle(Request $request) : View
    {
        $allAppInfos = GetAppList::run($request);
        $allGitProjects = GetRepositories::run($request);

        $isBadCredentials = $allGitProjects->status() == Response::HTTP_UNAUTHORIZED;

        return view('appinfo-form')->with([
            'all_appstore_apps' => $allAppInfos->getData(),
            'github_auth_failed' => $isBadCredentials,
            'github_projects' => ($isBadCredentials)
                ? collect()
                : $allGitProjects->getData()->response
        ]);
    }
}

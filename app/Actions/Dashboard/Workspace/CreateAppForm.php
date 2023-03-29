<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;

use App\Actions\Api\Github\GetRepositories;
use App\Actions\Api\AppStoreConnect\GetAppList;

class CreateAppForm
{
    use AsAction;

    public function handle() : View
    {
        $allAppInfos = GetAppList::run();
        $allGitProjects = GetRepositories::run();

        return view('appinfo-form')->with([
            'all_appstore_apps' => $allAppInfos->getData(),
            'github_auth_failed' => $allGitProjects->status() !== Response::HTTP_OK,
            'github_projects' => $allGitProjects->getData()->response,
        ]);
    }
}

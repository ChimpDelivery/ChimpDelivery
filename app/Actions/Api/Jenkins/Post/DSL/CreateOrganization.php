<?php

namespace App\Actions\Api\Jenkins\Post\DSL;

use Lorisleiva\Actions\Concerns\AsAction;

use App\Events\WorkspaceChanged;
use App\Services\JenkinsService;

/// Creates Workspace Folder in Jenkins when Dashboard Workspace created
class CreateOrganization
{
    use AsAction;

    public function handle(WorkspaceChanged $event) : void
    {
        ///
        if (!app()->isLocal())
        {
            return;
        }

        $request = $event->request;
        if (empty($request->validated('organization_name')))
        {
            return;
        }

        // Job url that contains Jenkins-DSL Plugin Action
        $url = config('jenkins.host') . '/job/Seed/buildWithParameters';
        $url .= implode('&', [
            "?GIT_USERNAME={$request->validated('organization_name')}",
            "GIT_ACCESS_TOKEN={$request->validated('personal_access_token')}",
            "GITHUB_TOPIC={$request->validated('topic_name')}",
            "REPO_OWNER={$request->validated('organization_name')}",
            "TESTFLIGHT_USERNAME={$request->validated('usermail')}",
            "TESTFLIGHT_PASSWORD={$request->validated('app_specific_pass')}"
        ]);

        app(JenkinsService::class)->GetJenkinsUser()->post($url);
    }
}

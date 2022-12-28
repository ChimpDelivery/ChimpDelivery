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
        if (!$event->workspace->wasRecentlyCreated)
        {
            return;
        }

        $validated = $event->request->safe();

        // Job url that contains Jenkins-DSL Plugin Action
        $url = config('jenkins.host') . '/job/Seed/buildWithParameters';
        $url .= implode('&', [
            "?GIT_USERNAME={$validated->organization_name}",
            "GIT_ACCESS_TOKEN={$validated->personal_access_token}",
            "GITHUB_TOPIC={$validated->topic_name}",
            "REPO_OWNER={$validated->organization_name}"
        ]);

        app(JenkinsService::class)->GetJenkinsUser()->post($url);
    }
}

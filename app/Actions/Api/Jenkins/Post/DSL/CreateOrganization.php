<?php

namespace App\Actions\Api\Jenkins\Post\DSL;

use Lorisleiva\Actions\Concerns\AsAction;

use App\Events\WorkspaceChanged;
use App\Services\JenkinsService;

use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

/// Creates/Updates Workspace Folder in Jenkins when Dashboard Workspace created
class CreateOrganization
{
    use AsAction;

    public function handle(WorkspaceChanged $event) : void
    {
        $request = $event->request;
        if (empty($request->validated('organization_name')))
        {
            return;
        }

        $url = $this->GetJobUrl();
        $url .= $this->GetJobParams($event->request);

        app(JenkinsService::class)->GetJenkinsUser()->post($url);
    }

    // Job url that contains Jenkins-DSL Plugin Action
    public function GetJobUrl() : string
    {
        return implode('/', [
            config('jenkins.host'),
            'job',
            config('jenkins.seeder'),
            'buildWithParameters?',
        ]);
    }

    public function GetJobParams(StoreWorkspaceSettingsRequest $request) : string
    {
        return implode('&', [
            // source control related
            "GIT_USERNAME={$request->validated('organization_name')}",
            "GIT_ACCESS_TOKEN={$request->validated('personal_access_token')}",
            "GITHUB_TOPIC={$request->validated('topic_name')}",
            "REPO_OWNER={$request->validated('organization_name')}",

            // delivery platform related
            "TESTFLIGHT_USERNAME={$request->validated('usermail')}",
            "TESTFLIGHT_PASSWORD={$request->validated('app_specific_pass')}",
        ]);
    }
}

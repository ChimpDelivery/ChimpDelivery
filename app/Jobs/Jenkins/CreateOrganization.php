<?php

namespace App\Jobs\Jenkins;

use App\Jobs\Jenkins\Interfaces\BaseJenkinsJob;

use App\Models\User;
use App\Models\Workspace;
use App\Services\JenkinsService;

/// Creates/Updates Workspace Folder in Jenkins when Dashboard Workspace created
class CreateOrganization extends BaseJenkinsJob
{
    public function __construct(
        public readonly Workspace $workspace,
        public readonly User $workspaceAdmin,
    ) { }

    public function handle() : void
    {
        $url = $this->GetJobUrl();
        $url .= $this->GetJobParams($this->workspace, $this->workspaceAdmin);

        app(JenkinsService::class)->SetUser($this->workspaceAdmin)->GetHttpClient()->post($url);
    }

    public function asJob() : void
    {
        $this->handle();
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

    // parameter references: https://github.com/TalusStudio/TalusWebBackend-JenkinsDSL/blob/master/Jenkinsfile
    public function GetJobParams(Workspace $workspace, User $workspaceAdmin) : string
    {
        $githubSetting = $workspace->githubSetting;
        $tfSetting = $workspace->appleSetting;

        return implode('&', [
            // dashboard-auth related
            "DASHBOARD_TOKEN={$workspaceAdmin->createApiToken(config('workspaces.jenkins_token_name'))}",

            // source control related
            "GIT_USERNAME={$githubSetting->organization_name}",
            "GIT_ACCESS_TOKEN={$githubSetting->personal_access_token}",
            "GITHUB_TOPIC={$githubSetting->topic_name}",
            "REPO_OWNER={$githubSetting->organization_name}",

            // delivery platform related
            "TESTFLIGHT_USERNAME={$tfSetting->usermail}",
            "TESTFLIGHT_PASSWORD={$tfSetting->app_specific_pass}",
        ]);
    }
}

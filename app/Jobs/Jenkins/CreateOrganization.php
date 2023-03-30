<?php

namespace App\Jobs\Jenkins;

use App\Jobs\Jenkins\Interfaces\BaseJenkinsJob;

use Illuminate\Support\Facades\App;

use App\Models\User;
use App\Models\Workspace;
use App\Services\JenkinsService;

/// Creates/Updates Workspace Folder in Jenkins when Dashboard Workspace created
class CreateOrganization extends BaseJenkinsJob
{
    public function __construct(
        public readonly Workspace $workspace,
        public readonly User $workspaceAdmin,
    ) {
    }

    public function handle() : void
    {
        $url = $this->GetJobUrl();
        $url .= $this->GetJobParams($this->workspace, $this->workspaceAdmin);

        $jenkinsService = App::makeWith(JenkinsService::class, [ 'user' => $this->workspaceAdmin ]);
        $jenkinsService->GetHttpClient()->post($url);
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
        $appleSetting = $workspace->appleSetting;

        return http_build_query([
            // dashboard-auth
            'DASHBOARD_URL' => config('app.url'),
            'DASHBOARD_TOKEN' => $workspaceAdmin->createApiToken(config('workspaces.jenkins_token_name')),

            // source control
            'GIT_USERNAME' => $githubSetting->organization_name,
            'GIT_ACCESS_TOKEN' => $githubSetting->personal_access_token,
            'GITHUB_TOPIC' => $githubSetting->topic_name,
            'REPO_OWNER' => $githubSetting->organization_name,

            // delivery platforms
            // ios
            'TESTFLIGHT_USERNAME' => $appleSetting->usermail,
            'TESTFLIGHT_PASSWORD' => $appleSetting->app_specific_pass,

            // android
            'ANDROID_KEYSTORE_PATH' => 'Assets/Settings/Key.keystore:TestPass_123',
            'ANDROID_KEYSTORE_PASS' => '3434Talus!',
        ]);
    }
}

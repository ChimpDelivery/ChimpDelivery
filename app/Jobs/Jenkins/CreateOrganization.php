<?php

namespace App\Jobs\Jenkins;

use App\Jobs\Interfaces\BaseJob;

use Illuminate\Support\Facades\App;

use App\Models\User;
use App\Models\Workspace;
use App\Services\JenkinsService;

/// Creates/Updates Workspace Folder in Jenkins when Dashboard Workspace created
class CreateOrganization extends BaseJob
{
    public function __construct(
        public readonly Workspace $workspace,
        public readonly User $user,
    ) {
    }

    public function handle() : void
    {
        $url = $this->GetJobUrl();
        $url .= $this->GetJobParams();

        $jenkinsService = App::makeWith(JenkinsService::class, [
            'user' => $this->user,
        ]);
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

    // parameter references: https://github.com/ChimpDelivery/ChimpDelivery-JenkinsDSL/blob/master/Jenkinsfile
    public function GetJobParams() : string
    {
        $githubSetting = $this->workspace->githubSetting;
        $appleSetting = $this->workspace->appleSetting;
        $googlePlaySetting = $this->workspace->googlePlaySetting;

        return http_build_query([
            // dashboard-auth
            'DASHBOARD_URL' => config('app.url'),
            'DASHBOARD_TOKEN' => $this->user->createApiToken(config('workspaces.jenkins_token_name')),

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
            // 'ANDROID_KEYSTORE_FILE' => $googlePlaySetting->keystore_file,
            'ANDROID_KEYSTORE_PASS' => $googlePlaySetting->keystore_pass,
        ]);
    }
}

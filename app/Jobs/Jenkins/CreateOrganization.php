<?php

namespace App\Jobs\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Workspace;
use App\Services\JenkinsService;

/// Creates/Updates Workspace Folder in Jenkins when Dashboard Workspace created
class CreateOrganization implements ShouldQueue, ShouldBeEncrypted
{
    use AsAction;

    use Queueable;
    use SerializesModels;

    private User $workspaceAdmin;
    private PendingRequest $jenkinsUser;

    public function __construct()
    {
        $this->workspaceAdmin = Auth::user();
        $this->jenkinsUser = app(JenkinsService::class)->GetJenkinsUser();
    }

    public function handle(Workspace $workspace)
    {
        $url = $this->GetJobUrl();
        $url .= $this->GetJobParams($workspace);

        return $this->jenkinsUser->post($url);
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

    public function GetJobParams(Workspace $workspace) : string
    {
        $githubSetting = $workspace->githubSetting;
        $tfSetting = $workspace->appleSetting;

        return implode('&', [
            "DASHBOARD_TOKEN={$this->workspaceAdmin->createApiToken('jenkins-key')}",

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

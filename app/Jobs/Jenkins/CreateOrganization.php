<?php

namespace App\Jobs\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Queue\SerializesModels;

use App\Models\Workspace;
use App\Services\JenkinsService;

/// Creates/Updates Workspace Folder in Jenkins when Dashboard Workspace created
class CreateOrganization implements ShouldQueue
{
    use AsAction;

    use Queueable;
    use SerializesModels;

    private PendingRequest $jenkinsUser;

    public function __construct()
    {
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

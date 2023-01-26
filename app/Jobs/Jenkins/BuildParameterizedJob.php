<?php

namespace App\Jobs\Jenkins;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;

use App\Models\User;
use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Actions\Api\Jenkins\JobPlatform;
use App\Actions\Api\S3\Provision\GetProvisionProfile;

// Populates Jenkinsfile parameters and build job
class BuildParameterizedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $branch = 'master';

    public function __construct(
        public readonly AppInfo $app,
        public readonly array $inputs,
        public readonly User $user,
    ) { }

    public function handle() : array
    {
        // send request
        $response = app(JenkinsService::class)
            ->InjectUser($this->user)
            ->PostResponse($this->CreateUrl($this->app, $this->inputs));

        $isResponseSucceed = $response->jenkins_status == Response::HTTP_CREATED;

        return [
            'success' => $isResponseSucceed,
            'message' => $isResponseSucceed ? "No Error" : "Error Code: {$response->jenkins_status}"
        ];
    }

    private function CreateUrl(AppInfo $app, array $inputs) : string
    {
        return implode('/', [
            "/job/{$app->project_name}/job",
            $this->branch,
            "buildWithParameters?{$this->GetParamsAsString($inputs)}",
        ]);
    }

    private function GetParamsAsString(array $inputs) : string
    {
        return $this->GetParams($inputs)->implode('&');
    }

    // parameter references: https://github.com/TalusStudio/TalusWebBackend-JenkinsDSL/blob/master/files/Jenkinsfile
    private function GetParams(array $inputs) : Collection
    {
        return collect([
            'INVOKE_PARAMETERS' => 'false',
            'PLATFORM' => $inputs['platform'],
            'APP_ID' => $inputs['id'],
            'STORE_BUILD_VERSION' => $inputs['store_version'],
            'STORE_CUSTOM_BUNDLE_VERSION' => $inputs['store_custom_version'] ?: 'false',
            'STORE_BUNDLE_VERSION' => $inputs['store_build_number'] ?: 1,
            'INSTALL_SDK' => !empty($inputs['install_backend']) ? 'true' : 'false',
        ])->merge($this->GetPlatformParams($inputs['platform']))
            ->map(fn ($val, $key) => "{$key}={$val}")
            ->values();
    }

    private function GetPlatformParams(string $platformName) : Collection
    {
        $platform = JobPlatform::tryFrom($platformName) ?? JobPlatform::Appstore->value;
        if ($platform === JobPlatform::Appstore)
        {
            $profile = GetProvisionProfile::run($this->user, $this->app->workspace)->headers;

            return collect([
                'DASHBOARD_PROFILE_UUID' => $profile->get('Dashboard-Provision-Profile-UUID'),
                'DASHBOARD_TEAM_ID' => $profile->get('Dashboard-Team-ID'),
            ]);
        }

        return collect();
    }
}

<?php

namespace App\Jobs\Jenkins;

use App\Jobs\Jenkins\Interfaces\BaseJenkinsJob;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

use App\Models\User;
use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Actions\Api\Jenkins\JobPlatform;
use App\Actions\Api\S3\Provision\GetProvisionProfile;

// Populates Jenkinsfile parameters and build job
class BuildParameterizedJob extends BaseJenkinsJob
{
    private const DEFAULT_BRANCH = 'master';
    private const DEFAULT_STORE_CUSTOM_BUNDLE_VERSION = 'false';
    private const DEFAULT_STORE_BUNDLE_VERSION = 1;

    public function __construct(
        public readonly AppInfo $app,
        public readonly array $inputs,
        public readonly User $user,
    ) {
    }

    public function handle() : array
    {
        $jenkinsService = App::makeWith(JenkinsService::class, [ 'user' => $this->user ]);

        // send request
        $response = $jenkinsService->PostResponse($this->CreateUrl());
        $isResponseSucceed = $response->jenkins_status === Response::HTTP_CREATED;

        return [
            'success' => $isResponseSucceed,
            'message' => $isResponseSucceed ? 'No Error' : "Error Code: {$response->jenkins_status}",
        ];
    }

    public function CreateUrl() : string
    {
        return implode('/', [
            "/job/{$this->app->project_name}/job",
            self::DEFAULT_BRANCH,
            'buildWithParameters?' . http_build_query($this->GetParams()->toArray()),
        ]);
    }

    // parameter references: https://github.com/TalusStudio/TalusWebBackend-JenkinsDSL/blob/master/files/Jenkinsfile
    private function GetParams() : Collection
    {
        return collect([
            'INVOKE_PARAMETERS' => 'false',
            'PLATFORM' => $this->inputs['platform'],
            'APP_ID' => $this->inputs['id'],
            'STORE_BUILD_VERSION' => $this->inputs['store_version'],
            'STORE_CUSTOM_BUNDLE_VERSION' => $this->inputs['store_custom_version'] ?? self::DEFAULT_STORE_CUSTOM_BUNDLE_VERSION,
            'STORE_BUNDLE_VERSION' => $this->inputs['store_build_number'] ?? self::DEFAULT_STORE_BUNDLE_VERSION,
            'UNITY_VERSION' => '2021.3.5f1',
        ])->merge($this->GetPlatformParams($this->inputs['platform']));
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

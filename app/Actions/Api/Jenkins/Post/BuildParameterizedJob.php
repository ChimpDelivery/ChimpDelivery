<?php

namespace App\Actions\Api\Jenkins\Post;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Actions\Api\Jenkins\JobPlatform;
use App\Http\Requests\Jenkins\BuildRequest;
use App\Actions\Api\S3\Provision\GetProvisionProfile;
use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

// Populates Jenkinsfile parameters and build job
class BuildParameterizedJob extends BaseJenkinsAction
{
    private string $branch = 'master';

    public function handle(BuildRequest $request, JenkinsService $service) : array
    {
        $app = Auth::user()->workspace->apps()->findOrFail($request->validated('id'));

        // send request
        $response = $service->PostResponse($this->CreateUrl($app, $request));
        $isResponseSucceed = $response->jenkins_status == Response::HTTP_CREATED;

        return [
            'success' => $isResponseSucceed,
            'message' => $isResponseSucceed
                ? "<b>{$app->project_name}</b>, building for <b>{$request->validated('platform')}</b>..."
                : "Error Code: {$response->jenkins_status}"
        ];
    }

    private function CreateUrl(AppInfo $app, BuildRequest $request) : string
    {
        return implode('/', [
            "/job/{$app->project_name}/job",
            $this->branch,
            "buildWithParameters?{$this->GetParamsAsString($request)}",
        ]);
    }

    private function GetParamsAsString(BuildRequest $request) : string
    {
        return $this->GetParams($request)->implode('&');
    }

    // parameter references: https://github.com/TalusStudio/TalusWebBackend-JenkinsDSL/blob/master/files/Jenkinsfile
    private function GetParams(BuildRequest $request) : Collection
    {
        return collect([
            'INVOKE_PARAMETERS' => 'false',
            'PLATFORM' => $request->validated('platform'),
            'APP_ID' => $request->validated('id'),
            'STORE_BUILD_VERSION' => $request->validated('store_version'),
            'STORE_CUSTOM_BUNDLE_VERSION' => $request->validated('store_custom_version') ?: 'false',
            'STORE_BUNDLE_VERSION' => $request->validated('store_build_number') ?: 1,
            'INSTALL_SDK' => !empty($request->validated('install_backend')) ? 'true' : 'false',
        ])->merge($this->GetPlatformParams($request->validated('platform')))
            ->map(fn ($val, $key) => "{$key}={$val}")
            ->values();
    }

    private function GetPlatformParams(string $platformName) : Collection
    {
        $platform = JobPlatform::tryFrom($platformName) ?? JobPlatform::Appstore->value;
        if ($platform === JobPlatform::Appstore)
        {
            $profile = GetProvisionProfile::run()->headers;

            return collect([
                'DASHBOARD_PROFILE_UUID' => $profile->get('Dashboard-Provision-Profile-UUID'),
                'DASHBOARD_TEAM_ID' => $profile->get('Dashboard-Team-ID'),
            ]);
        }

        return collect();
    }
}

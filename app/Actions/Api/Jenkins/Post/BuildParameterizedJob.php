<?php

namespace App\Actions\Api\Jenkins\Post;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use App\Services\JenkinsService;
use App\Actions\Api\Jenkins\JobPlatform;
use App\Http\Requests\Jenkins\BuildRequest;
use App\Actions\Api\S3\Provision\GetProvisionProfile;
use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

class BuildParameterizedJob extends BaseJenkinsAction
{
    private string $branch = 'master';

    public function handle(BuildRequest $request, JenkinsService $service) : array
    {
        $app = Auth::user()->workspace->apps()->findOrFail($request->validated('id'));
        $buildUrl = $this->CreateJobUrl($app->project_name, $this->GetParamsAsUrl($request));

        $response = $service->PostResponse($buildUrl);
        $responseCode = $response->jenkins_status;

        $isResponseSucceed = $responseCode == Response::HTTP_CREATED;
        $responseMessage = ($isResponseSucceed)
            ? "<b>{$app->project_name}</b>, building for <b>{$request->validated('platform')}</b>..."
            : "Error Code: {$responseCode}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }

    private function CreateJobUrl($projectName, $parametersAsUrl) : string
    {
        return implode('/', [
            "/job/{$projectName}/job",
            $this->branch,
            "buildWithParameters?{$parametersAsUrl}",
        ]);
    }

    private function GetParamsAsUrl(BuildRequest $request) : string
    {
        return $this->GetParams($request)->implode('&');
    }

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

    private function GetPlatformParams($platform) : Collection
    {
        if ($platform === JobPlatform::Appstore->value)
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

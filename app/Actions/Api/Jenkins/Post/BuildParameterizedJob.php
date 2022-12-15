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

        // todo:extract platform-specific parameters
        if ($request->validated('platform') === JobPlatform::Appstore->value)
        {
            $profileFile = GetProvisionProfile::run();
            $provisionProfileUuid = $profileFile->headers->get('Dashboard-Provision-Profile-UUID');
            $provisionTeamId = $profileFile->headers->get('Dashboard-Team-ID');
            $buildUrl .= "&DASHBOARD_PROFILE_UUID={$provisionProfileUuid}";
            $buildUrl .= "&DASHBOARD_TEAM_ID={$provisionTeamId}";
        }

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
        ])->map(fn ($val, $key) => "{$key}={$val}")->values();
    }
}

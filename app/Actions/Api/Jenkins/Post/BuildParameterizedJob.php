<?php

namespace App\Actions\Api\Jenkins\Post;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\AppInfo;
use App\Services\JenkinsService;
use App\Http\Requests\Jenkins\BuildRequest;
use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;
use App\Actions\Api\AppStoreConnect\Provision\GetProvisionProfile;

class BuildParameterizedJob extends BaseJenkinsAction
{
    private string $branch = "master";

    public function handle(BuildRequest $request) : array
    {
        $validated = $request->validated();
        $validated['store_custom_version'] ??= 'false';
        $validated['store_build_number'] = ($validated['store_custom_version'] == 'true')
            ? ($validated['store_build_number'] ?? 1)
            : 0;

        $app = AppInfo::find($validated['id']);

        $provisionProfileUuid = '';

        if ($validated['platform'] === 'Appstore')
        {
            $profileFile = GetProvisionProfile::run();
            $provisionProfileUuid = $profileFile->headers->get('Dashboard-Provision-Profile-UUID');
        }

        $provisionFileName = Str::of(Auth::user()->workspace->appstoreConnectSign->provision_profile)
                        ->explode('/')
                        ->last();

        $url = "/job/{$app->project_name}/job/{$this->branch}/buildWithParameters"
            ."?INVOKE_PARAMETERS=false"
            ."&PLATFORM={$validated['platform']}"
            ."&APP_ID={$validated['id']}"
            ."&STORE_BUILD_VERSION={$validated['store_version']}"
            ."&STORE_CUSTOM_BUNDLE_VERSION={$validated['store_custom_version']}"
            ."&STORE_BUNDLE_VERSION={$validated['store_build_number']}"
            ."&DASHBOARD_PROFILE_NAME={$provisionFileName}"
            ."&DASHBOARD_PROFILE_UUID={$provisionProfileUuid}";

        $response = app(JenkinsService::class)->PostResponse($url);
        $responseCode = $response->jenkins_status;

        $isResponseSucceed = $responseCode == Response::HTTP_CREATED;
        $responseMessage = ($isResponseSucceed)
            ? "<b>{$app->project_name}</b>, building for <b>{$validated['platform']}</b>..."
            : "Error Code: {$responseCode}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }
}

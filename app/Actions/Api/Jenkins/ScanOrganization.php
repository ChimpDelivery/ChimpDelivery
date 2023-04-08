<?php

namespace App\Actions\Api\Jenkins;

use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

use Laravel\Pennant\Feature;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Features\AppBuild;
use App\Services\JenkinsService;

class ScanOrganization extends BaseJenkinsAction
{
    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) {
    }

    public function handle() : array
    {
        $response = $this->jenkinsService->PostResponse('/build?delay=0');

        $isResponseSucceed = $response->jenkins_status === Response::HTTP_OK;
        $responseMessage = ($isResponseSucceed)
            ? 'Repository scanning begins.'
            : "Repository scanning could not run! Error Code: {$response->jenkins_status}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }

    public function authorize() : bool
    {
        return Auth::user()->can('scan jobs') && Feature::active(AppBuild::class);
    }
}

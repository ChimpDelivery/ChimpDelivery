<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;

use App\Traits\AsActionResponse;
use App\Services\AppStoreConnectService;
use App\Http\Requests\AppStoreConnect\StoreBundleRequest;

// api reference: https://developer.apple.com/documentation/appstoreconnectapi/register_a_new_bundle_id
class CreateBundleId
{
    use AsAction;
    use AsActionResponse;

    public function __construct(
        private readonly AppStoreConnectService $appStoreConnectService
    ) { }

    public function handle(StoreBundleRequest $request) : array
    {
        $bundleData = $this->PrepareRequestData(
            $request->validated('bundle_id'),
            $request->validated('bundle_name')
        );

        // request
        $createBundle = $this->appStoreConnectService->GetHttpClient()
            ->withBody(json_encode($bundleData))
            ->post(config('appstore.endpoint') . '/bundleIds');

        // response
        $createBundleResponse = $createBundle->json();
        $responseHasError = isset($createBundleResponse['errors']);
        $responseMessage = ($responseHasError)
            ? $this->GetErrorMessage($createBundleResponse)
            : "Bundle: <b>{$request->validated('bundle_id')}</b> created!";

        return [
            'success' => !$responseHasError,
            'message' => $responseMessage,
        ];
    }

    private function PrepareRequestData(string $bundleId, string $bundleName) : array
    {
        return [
            'data' => [
                'attributes' => [
                    'identifier' => $bundleId,
                    'name' => $bundleName,
                    'platform' => 'IOS',
                ],
                'type' => 'bundleIds',
            ]
        ];
    }

    private function GetErrorMessage($createBundleResponse) : string
    {
        $error = $createBundleResponse['errors'][0];
        return $error['detail'] . " (Status code: {$error['status']})";
    }

    public function authorize(StoreBundleRequest $request) : bool
    {
        return $request->user()->can('create bundle');
    }
}

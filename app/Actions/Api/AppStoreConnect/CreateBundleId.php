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

    public function handle(StoreBundleRequest $request, AppStoreConnectService $service) : array
    {
        $data = [
            'data' => [
                'attributes' => [
                    'identifier' => $request->validated('bundle_id'),
                    'name' => $request->validated('bundle_name'),
                    'platform' => 'IOS'
                ],
                'type' => 'bundleIds'
            ]
        ];

        $createBundle = $service->GetClient()
            ->withBody(json_encode($data), 'application/json')
            ->post(AppStoreConnectService::API_URL.'/bundleIds');

        $createBundleResponse = $createBundle->json();

        $responseMessage = 'Bundle: <b>' . $request->validated('bundle_id') . '</b> created!';
        $responseHasError = isset($createBundleResponse['errors']);
        if ($responseHasError)
        {
            $error = $createBundleResponse['errors'][0];
            $responseMessage = $error['detail'] . " (Status code: {$error['status']})";
        }

        return [
            'success' => $responseHasError === false,
            'message' => $responseMessage,
        ];
    }

    public function authorize(StoreBundleRequest $request) : bool
    {
        return !Auth::user()->isNew() && Auth::user()->can('create bundle');
    }
}

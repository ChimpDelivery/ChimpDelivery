<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

use App\Traits\AsActionResponse;
use App\Services\AppStoreConnectService;
use App\Http\Requests\AppStoreConnect\StoreBundleRequest;

class CreateBundleId
{
    use AsAction;
    use AsActionResponse;

    public function handle(StoreBundleRequest $request) : array
    {
        $storeService = new AppStoreConnectService($request);
        $generatedToken = $storeService->CreateToken()->getData()->appstore_token;

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

        $createBundle = Http::withToken($generatedToken)
            ->withBody(json_encode($data), 'application/json')
            ->post(AppStoreConnectService::$API_URL.'/bundleIds');

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
        return $request->expectsJson()
            ? true
            : Auth::user()->can('create bundle');
    }
}

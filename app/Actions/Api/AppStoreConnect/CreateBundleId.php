<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

use App\Http\Requests\AppStoreConnect\StoreBundleRequest;

use App\Services\AppStoreConnectService;

class CreateBundleId
{
    use AsAction;

    public function handle(StoreBundleRequest $request) : JsonResponse|RedirectResponse
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

        // send json response
        if ($request->expectsJson())
        {
            return response()->json([
                'status' => $createBundleResponse
            ]);
        }

        // send web response
        if (isset($createBundleResponse['errors']))
        {
            $error = $createBundleResponse['errors'][0];

            return to_route('create_bundle')
                ->withErrors([ 'bundle_id' => $error['detail'] . " (Status code: {$error['status']})" ])
                ->withInput();
        }

        return to_route('index')->with('success', 'Bundle: <b>' . $request->validated('bundle_id') . '</b> created!');
    }
}

<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

use App\Http\Requests\AppStoreConnect\StoreBundleRequest;

use App\Services\AppStoreConnectService;

class CreateBundle
{
    use AsAction;

    public function handle(StoreBundleRequest $request) : JsonResponse
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

        $appList = Http::withToken($generatedToken)
            ->withBody(json_encode($data), 'application/json')
            ->post(AppStoreConnectService::$API_URL.'/bundleIds');

        return response()->json([
            'status' => $appList->json()
        ]);
    }
}

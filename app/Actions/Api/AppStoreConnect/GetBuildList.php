<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Http;

use App\Services\AppStoreConnectService;

class GetBuildList
{
    use AsAction;

    public function handle(Request $request) : JsonResponse
    {
        $storeService = new AppStoreConnectService($request);
        $generatedToken = $storeService->CreateToken()->getData()->appstore_token;

        $appList = Http::withToken($generatedToken)->get(AppStoreConnectService::$API_URL.'/builds');
        $builds = collect(json_decode($appList)->data);

        return response()->json([
            'builds' => $builds->pluck('attributes.uploadedDate', 'attributes.version')->sortKeys()
        ]);
    }
}

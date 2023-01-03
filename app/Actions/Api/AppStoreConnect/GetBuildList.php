<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Services\AppStoreConnectService;

/// no use case
/// not working correctly
class GetBuildList
{
    use AsAction;

    public function handle() : JsonResponse
    {
        $service = app(AppStoreConnectService::class);

        $appList = $service->GetClient()->get(AppStoreConnectService::API_URL.'/builds');
        $builds = collect(json_decode($appList)->data);

        return response()->json([
            'builds' => $builds->pluck('attributes.uploadedDate', 'attributes.version')->sortKeys()
        ]);
    }
}

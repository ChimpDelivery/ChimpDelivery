<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Services\AppStoreConnectService;

// api reference: https://developer.apple.com/documentation/appstoreconnectapi/list_apps
class GetStoreApps
{
    use AsAction;

    public function __construct(
        private readonly AppStoreConnectService $appStoreConnectService
    ) {
    }

    public function handle() : JsonResponse
    {
        $appstoreApps = $this->appStoreConnectService->GetHttpClient()->get(
            config('appstore.endpoint')
            .'/apps?fields[apps]=name,bundleId'
            .'&limit=' . config('appstore.item_limit')
            .'&filter[appStoreVersions.platform]=IOS'
            .'&filter[appStoreVersions.appStoreState]=PREPARE_FOR_SUBMISSION'
        );

        $sortedAppCollection = collect(($appstoreApps->failed()) ? [] : json_decode($appstoreApps)->data);
        $sortedAppList = $sortedAppCollection->sortByDesc('id');

        return response()->json([ 'app_list' => $sortedAppList ]);
    }
}

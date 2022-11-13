<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

use App\Services\AppStoreConnectService;

// api reference: https://developer.apple.com/documentation/appstoreconnectapi/list_apps
class GetFullAppInfo
{
    use AsAction;

    public function handle() : JsonResponse
    {
        $generatedToken = app(AppStoreConnectService::class)
            ->CreateToken()
            ->getData()
            ->appstore_token;

        $appstoreApps = Http::withToken($generatedToken)->get(AppStoreConnectService::$API_URL
            .'/apps?fields[apps]=name,bundleId&limit='
            .config('appstore.item_limit')
            .'&filter[appStoreVersions.platform]=IOS&filter[appStoreVersions.appStoreState]=PREPARE_FOR_SUBMISSION'
        );

        $sortedAppCollection = collect(($appstoreApps->failed()) ? [] : json_decode($appstoreApps)->data);
        $sortedAppList = $sortedAppCollection->sortByDesc('id');

        return response()->json([ 'app_list' => $sortedAppList ]);
    }
}

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
        $appstoreClient = $this->appStoreConnectService->GetHttpClient();
        $request = $appstoreClient->get($this->GetApiRoute());

        $apps = collect($request->failed() ? [] : $request->json('data'));
        $sortedApps = $apps->sortByDesc('id');

        return response()->json([
            'app_list' => $sortedApps,
        ]);
    }

    private function GetApiRoute() : string
    {
        return config('appstore.endpoint')
            . '/apps?'
            . urldecode(http_build_query([
                'fields[apps]' => 'name,bundleId',
                'limit' => config('appstore.item_limit'),
                'filter[appStoreVersions.platform]' => 'IOS',
                'filter[appStoreVersions.appStoreState]' => 'PREPARE_FOR_SUBMISSION',
            ]));
    }
}

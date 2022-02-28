<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DataProviders\AppStoreConnectDataProvider;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class AppStoreConnectController extends Controller
{
    public function GetToken() : string
    {
        return AppStoreConnectDataProvider::getToken();
    }

    public function GetFullAppInfo() : JsonResponse
    {
        return AppStoreConnectDataProvider::getFullInfo();
    }

    public function GetAppList() : JsonResponse
    {
        return AppStoreConnectDataProvider::getAppList();
    }

    public function GetAllBundles() : JsonResponse
    {
        return AppStoreConnectDataProvider::getAllBundles();
    }

    public function GetBuildList(Request $request) : JsonResponse
    {
        return AppStoreConnectDataProvider::getAllBuilds("https://api.appstoreconnect.apple.com/v1/apps/$request->appstore_id/builds");
    }

    public function ClearCache() : JsonResponse
    {
        return response()->json([
            'status' => Cache::flush() ? 200 : 400
        ]);
    }
}

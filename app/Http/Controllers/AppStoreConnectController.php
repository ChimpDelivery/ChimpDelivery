<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DataProviders\AppStoreConnectDataProvider;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class AppStoreConnectController extends Controller
{
    public function __construct()
    {
        $this->middleware('cached-response');
    }

    public function GetToken(Request $request) : JsonResponse
    {
        return response()->json([
            'appstore_token' => AppStoreConnectDataProvider::getToken()
        ]);
    }

    public function GetFullAppInfo(Request $request) : JsonResponse
    {
        return response()->json([
            'appstore_full' => AppStoreConnectDataProvider::getFullInfo()
        ]);
    }

    public function GetAppList(Request $request) : JsonResponse
    {
        return response()->json([
            'appstore_apps' => AppStoreConnectDataProvider::getAppList()
        ]);
    }

    public function GetAllBundles(Request $request) : JsonResponse
    {
        return response()->json([
            'appstore_bundles' => AppStoreConnectDataProvider::getAllBundles()
        ]);
    }

    public function GetBuildList(Request $request) : JsonResponse
    {
        return response()->json([
            'appstore_builds' => AppStoreConnectDataProvider::getAllBuilds("https://api.appstoreconnect.apple.com/v1/apps/$request->appstore_id/builds")
        ]);
    }

    public function ClearCache(Request $request) : JsonResponse
    {
        return response()->json([
            'status' => Cache::flush() ? 200 : 400
        ]);
    }
}

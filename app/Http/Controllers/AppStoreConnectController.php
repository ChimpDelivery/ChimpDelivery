<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiProviders\AppStoreConnectApi;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class AppStoreConnectController extends Controller
{
    public function GetToken() : string
    {
        return AppStoreConnectApi::getToken();
    }

    public function GetFullAppInfo() : JsonResponse
    {
        return AppStoreConnectApi::getFullInfo();
    }

    public function GetAppList() : JsonResponse
    {
        return AppStoreConnectApi::getAppList();
    }

    public function GetAppDictionary() : JsonResponse
    {
        return AppStoreConnectApi::getAppDictionary();
    }

    public function GetAllBundles() : JsonResponse
    {
        return AppStoreConnectApi::getAllBundles();
    }

    public function ClearCache() : JsonResponse
    {
        return response()->json([
            'status' => Cache::flush() ? 200 : 400
        ]);
    }
}

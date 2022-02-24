<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DataProviders\AppStoreConnectDataProvider;
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

    public function GetAppDictionary() : JsonResponse
    {
        return AppStoreConnectDataProvider::getAppDictionary();
    }

    public function GetAllBundles() : JsonResponse
    {
        return AppStoreConnectDataProvider::getAllBundles();
    }

    public function ClearCache() : JsonResponse
    {
        return response()->json([
            'status' => Cache::flush() ? 200 : 400
        ]);
    }
}

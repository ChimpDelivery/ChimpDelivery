<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiProviders\AppStoreConnectApi;
use Illuminate\Support\Facades\Cache;

class AppStoreConnectController extends Controller
{
    /// app store connect apis
    public function GetToken()
    {
        return AppStoreConnectApi::getToken();
    }

    public function GetFullAppInfo()
    {
        return AppStoreConnectApi::getFullInfo();
    }

    public function GetAppList()
    {
        return AppStoreConnectApi::getAppList();
    }

    public function GetAppDictionary()
    {
        return AppStoreConnectApi::getAppDictionary();
    }

    public function GetAllBundles()
    {
        return AppStoreConnectApi::getAllBundles();
    }

    public function ClearCache()
    {
        return response()->json([
            'status' => Cache::flush() ? 200 : 400
        ]);
    }
}

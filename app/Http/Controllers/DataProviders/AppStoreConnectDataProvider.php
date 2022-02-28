<?php

namespace App\Http\Controllers\DataProviders;

use App\Http\Controllers\ApiProviders\AppStoreConnectApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AppStoreConnectDataProvider
{
    public static function getToken() : string
    {
        $header = [
            'alg' => 'ES256',
            'kid' => env('APPSTORECONNECT_KID'),
            'typ' => 'JWT',
        ];

        $payload = [
            'iss' => env('APPSTORECONNECT_ISSUER_ID'),
            'exp' => time() + 600,
            'aud' => 'appstoreconnect-v1'
        ];

        return AppStoreConnectApi::sign($payload, $header, env('APPSTORECONNECT_PRIVATE_KEY'));
    }

    public static function getFullInfo() : JsonResponse
    {
        if (Cache::has('cached_app_list'))
        {
            return response()->json([
                'cached_data' => true,
                'app_list' => Cache::get('cached_app_list')
            ]);
        }

        $token = self::getToken();

        $appList = Http::withToken($token)->get('https://api.appstoreconnect.apple.com/v1/apps');
        $fullAppList = json_decode($appList, true);

        Cache::put('cached_app_list', $fullAppList, now()->addMinutes(env('APPSTORECONNECT_CACHE_DURATION')));

        return response()->json([
            'cached_data' => false,
            'app_list' => $fullAppList
        ]);
    }

    public static function getAppList() : JsonResponse
    {
        $appList = self::getFullInfo()->getContent();
        $decodedAppList = json_decode($appList);

        $data = $decodedAppList->app_list->data;

        $apps = array();
        foreach ($data as $content)
        {
            $bundleId = $content->attributes->bundleId;
            $appName = $content->attributes->name;
            $appstoreId = $content->id;

            $apps []= array($bundleId, $appName, $appstoreId);
        }

        return response()->json([
            'apps' => $apps
        ]);
    }

    public static function getAllBundles() : JsonResponse
    {
        $bundleIds = array();
        $fullAppDictionary = json_decode(self::getAppList()->getContent());

        foreach ($fullAppDictionary->apps as $appBundleAndNamePair)
        {
            $bundleIds []= $appBundleAndNamePair[0];
        }

        return response()->json([
            'bundle_ids' => $bundleIds
        ]);
    }

    // https://api.appstoreconnect.apple.com/v1/apps/{appstore_id}/builds
    public static function getAllBuilds($appApiUrl) : JsonResponse
    {
        $token = self::getToken();

        $appList = Http::withToken($token)->get($appApiUrl);
        $fullAppList = json_decode($appList);

        $buildsCollection = collect($fullAppList->data);

        return response()->json([
            'builds' =>  $buildsCollection->pluck('attributes.uploadedDate', 'attributes.version')->sort()
        ]);
    }
}

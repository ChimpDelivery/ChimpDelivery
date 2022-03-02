<?php

namespace App\Http\Controllers\DataProviders;

use App\Http\Controllers\ApiProviders\AppStoreConnectApi;
use Illuminate\Http\JsonResponse;
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
            'exp' => time() + 120,
            'aud' => 'appstoreconnect-v1'
        ];

        return AppStoreConnectApi::sign($payload, $header, env('APPSTORECONNECT_PRIVATE_KEY'));
    }

    public static function getFullInfo() : JsonResponse
    {
        $appList = Http::withToken(self::getToken())->get('https://api.appstoreconnect.apple.com/v1/apps?fields[apps]=name,bundleId');

        return response()->json([
            'app_list' => $appList->json()
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

            $apps []= array('app_bundle' => $bundleId, 'app_name' => $appName, 'appstore_id' => $appstoreId);
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
            $bundleIds []= $appBundleAndNamePair->app_bundle;
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

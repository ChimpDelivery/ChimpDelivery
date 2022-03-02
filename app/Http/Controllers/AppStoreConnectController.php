<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiProviders\AppStoreConnectApi;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AppStoreConnectController extends Controller
{
    public function __construct()
    {
        // $this->middleware('cached-response');
    }

    public static function GetToken(Request $request) : JsonResponse
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

        return response()->json([
            'appstore_token' => AppStoreConnectApi::sign($payload, $header, env('APPSTORECONNECT_PRIVATE_KEY'))
        ]);
    }

    public function GetFullAppInfo(Request $request) : JsonResponse
    {
        $appList = Http::withToken($this->GetToken($request)->getData()->appstore_token)
            ->get('https://api.appstoreconnect.apple.com/v1/apps?fields[apps]=name,bundleId');

        return response()->json([
            'app_list' => $appList->json()
        ]);
    }

    public function GetAppList(Request $request) : JsonResponse
    {
        $appList = $this->GetFullAppInfo($request)->getContent();
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

    public function GetAllBundles(Request $request) : JsonResponse
    {
        $bundleIds = array();
        $fullAppDictionary = json_decode($this->GetAppList($request)->getContent());

        foreach ($fullAppDictionary->apps as $appBundleAndNamePair)
        {
            $bundleIds []= $appBundleAndNamePair->app_bundle;
        }

        return response()->json([
            'bundle_ids' => $bundleIds
        ]);
    }

    public function GetBuildList(Request $request) : JsonResponse
    {
        $appList = Http::withToken($this->GetToken($request)->getData()->appstore_token)
            ->get("https://api.appstoreconnect.apple.com/v1/builds");

        $builds = collect(json_decode($appList)->data);

        return response()->json([
            'builds' =>  $builds->pluck('attributes.uploadedDate', 'attributes.version')->sortKeys()
        ]);
    }

    public function ClearCache(Request $request) : JsonResponse
    {
        return response()->json([
            'status' => Cache::flush() ? 200 : 400
        ]);
    }
}

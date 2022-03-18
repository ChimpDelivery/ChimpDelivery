<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class AppStoreConnectController extends Controller
{
    private const API_URL = 'https://api.appstoreconnect.apple.com/v1';
    private const BUNDLE_ID_PREFIX = 'com.Talus';

    public function GetBundlePrefix() : string
    {
        return self::BUNDLE_ID_PREFIX;
    }

    public function GetToken(Request $request) : JsonResponse
    {
        $payload = [
            'iss' => config('appstore.issuer_id'),
            'exp' => time() + 120,
            'aud' => 'appstoreconnect-v1'
        ];

        return response()->json([
            'appstore_token' => JWT::encode($payload, config('appstore.private_key'), 'ES256', config('appstore.kid'))
        ]);
    }

    public function GetFullAppInfo(Request $request) : JsonResponse
    {
        $appList = Http::withToken($this->GetToken($request)->getData()->appstore_token)
            ->get(self::API_URL.'/apps?fields[apps]=name,bundleId&limit=100');

        return response()->json([
            'app_list' => $appList->json()
        ]);
    }

    public function GetAppList(Request $request) : JsonResponse
    {
        $appList = $this->GetFullAppInfo($request)->getData();
        $data = $appList->app_list->data;

        $apps = [];
        foreach ($data as $content) {
            $apps []= [
                'app_bundle' => $content->attributes->bundleId,
                'app_name' => $content->attributes->name,
                'appstore_id' => $content->id
            ];
        }

        return response()->json($apps);
    }

    public function GetSpecificApp(Request $request) : JsonResponse
    {
        $app = AppInfo::where('app_name', $request->projectName)->first();
        $response = [
            'app_bundle' => $app->app_bundle,
            'app_name' => $app->app_name
        ];

        return response()->json($response);
    }

    public function GetAllBundles(Request $request) : JsonResponse
    {
        $appList = Http::withToken($this->GetToken($request)->getData()->appstore_token)
            ->get(self::API_URL.'/bundleIds?fields[bundleIds]=name,identifier&limit=100&filter[platform]=IOS&sort=seedId');

        $bundleIds = collect(json_decode($appList)->data);
        $sortedBundleIds = $bundleIds->pluck('attributes.identifier');

        return response()->json([
            'bundle_ids' => $sortedBundleIds->reverse()->values()
        ]);
    }

    public function GetBuildList(Request $request) : JsonResponse
    {
        $appList = Http::withToken($this->GetToken($request)->getData()->appstore_token)
            ->get(self::API_URL.'/builds');

        $builds = collect(json_decode($appList)->data);

        return response()->json([
            'builds' => $builds->pluck('attributes.uploadedDate', 'attributes.version')->sortKeys()
        ]);
    }

    public function CreateBundle(Request $request)
    {
        $bundleIdAttributes = [
            'identifier' => self::BUNDLE_ID_PREFIX . '.' . $request->bundle_id,
            'name' => $request->bundle_name,
            'platform' => 'IOS'
        ];

        $body = [
            'attributes' => $bundleIdAttributes,
            'type' => 'bundleIds'
        ];

        $data = [
            'data' => $body
        ];

        $appList = Http::withToken($this->GetToken($request)->getData()->appstore_token)
            ->withBody(json_encode($data), 'application/json')
            ->post(self::API_URL.'/bundleIds');

        return response()->json([
            'status' => $appList->json()
        ]);
    }
}

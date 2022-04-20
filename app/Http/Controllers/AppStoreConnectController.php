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
            ->get(self::API_URL.'/apps?fields[apps]=name,bundleId&limit='.config('appstore.item_limit').'&filter[appStoreVersions.platform]=IOS');

        $sortedAppCollection = collect(json_decode($appList)->data);
        $sortedAppList = $sortedAppCollection->sortByDesc('id');

        return response()->json([
            'app_list' => $sortedAppList
        ]);
    }

    public function GetAppList(Request $request) : JsonResponse
    {
        $appList = $this->GetFullAppInfo($request)->getData();
        $data = $appList->app_list;

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
        $response = AppInfo::find($request->id, [
            'app_bundle',
            'app_name',
            'fb_app_id',
            'elephant_id',
            'elephant_secret'
        ]);
        
        return response()->json($response);
    }

    public function GetAllBundles(Request $request) : JsonResponse
    {
        $appList = Http::withToken($this->GetToken($request)->getData()->appstore_token)
            ->get(self::API_URL.'/bundleIds?fields[bundleIds]=name,identifier&limit='.config('appstore.item_limit').'&filter[platform]=IOS&sort=seedId');

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
            'identifier' => config('appstore.bundle_prefix') . '.' . $request->bundle_id,
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

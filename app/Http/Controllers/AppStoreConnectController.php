<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class AppStoreConnectController extends Controller
{
    /// related with app store connect.
    private const BUNDLE_ID_PREFIX = 'com.Talus';

    public function GetToken(Request $request) : JsonResponse
    {
        $payload = [
            'iss' => env('APPSTORECONNECT_ISSUER_ID'),
            'exp' => time() + 120,
            'aud' => 'appstoreconnect-v1'
        ];

        return response()->json([
            'appstore_token' => JWT::encode($payload, env('APPSTORECONNECT_PRIVATE_KEY'), 'ES256', env('APPSTORECONNECT_KID'))
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
        $appList = $this->GetFullAppInfo($request)->getData();
        $data = $appList->app_list->data;

        $apps = [];
        foreach ($data as $content)
        {
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
        $bundleIds = [];
        $fullAppDictionary = $this->GetAppList($request)->getData();

        foreach ($fullAppDictionary as $appBundleAndNamePair)
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
            ->post("https://api.appstoreconnect.apple.com/v1/bundleIds");

        return response()->json([
            'status' => $appList->json()
        ]);
    }
}

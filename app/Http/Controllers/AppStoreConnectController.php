<?php

namespace App\Http\Controllers;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;

use Firebase\JWT\JWT;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class AppStoreConnectController extends Controller
{
    private const API_URL = 'https://api.appstoreconnect.apple.com/v1';

    public function GetToken() : JsonResponse
    {
        $payload = [
            'iss' => config('appstore.issuer_id'),
            'exp' => time() + config('appstore.cache_duration') * 60,
            'aud' => 'appstoreconnect-v1'
        ];

        return response()->json([
            'appstore_token' => JWT::encode($payload, config('appstore.private_key'), 'ES256', config('appstore.kid'))
        ]);
    }

    public function GetFullAppInfo() : JsonResponse
    {
        $appList = Http::withToken($this->GetToken()->getData()->appstore_token)
            ->get(self::API_URL.'/apps?fields[apps]=name,bundleId&limit='.config('appstore.item_limit').'&filter[appStoreVersions.platform]=IOS');

        $sortedAppCollection = collect(json_decode($appList)->data);
        $sortedAppList = $sortedAppCollection->sortByDesc('id');

        return response()->json([
            'app_list' => $sortedAppList
        ]);
    }

    public function GetAppList() : JsonResponse
    {
        $appList = $this->GetFullAppInfo()->getData();
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

    public function GetBuildList() : JsonResponse
    {
        $appList = Http::withToken($this->GetToken()->getData()->appstore_token)
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

        $appList = Http::withToken($this->GetToken()->getData()->appstore_token)
            ->withBody(json_encode($data), 'application/json')
            ->post(self::API_URL.'/bundleIds');

        return response()->json([
            'status' => $appList->json()
        ]);
    }
}

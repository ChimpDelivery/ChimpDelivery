<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\AppStoreConnect\StoreBundleRequest;

use Firebase\JWT\JWT;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AppStoreConnectController extends Controller
{
    private const API_URL = 'https://api.appstoreconnect.apple.com/v1';

    public function GetToken() : JsonResponse
    {
        $appStoreSetting = Auth::user()->workspace->appStoreConnectSetting;

        $payload = [
            'iss' => $appStoreSetting->issuer_id,
            'exp' => time() + config('appstore.cache_duration') * 60,
            'aud' => 'appstoreconnect-v1'
        ];

        $token = null;

        try
        {
            $token = JWT::encode($payload,
                $appStoreSetting->private_key,
                'ES256',
                $appStoreSetting->kid,
            );
        }
        catch (\Exception $e)
        {
            return response()->json([
                'appstore_token' => null,
                'error' => 'Could not generate token'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([ 'appstore_token' => $token ]);
    }

    public function GetFullAppInfo() : JsonResponse
    {
        $appList = Http::withToken($this->GetToken()->getData()->appstore_token)
            ->get(self::API_URL.'/apps?fields[apps]=name,bundleId&limit='.config('appstore.item_limit').'&filter[appStoreVersions.platform]=IOS&filter[appStoreVersions.appStoreState]=PREPARE_FOR_SUBMISSION');

        $sortedAppCollection = collect(($appList->failed()) ? [] : json_decode($appList)->data);
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
        foreach ($data as $content)
        {
            $apps []= [
                'app_bundle' => $content->attributes->bundleId,
                'app_name' => $content->attributes->name,
                'appstore_id' => $content->id,
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

    public function CreateBundle(StoreBundleRequest $request)
    {
        $data = [
            'data' => [
                'attributes' => [
                    'identifier' => $request->validated('bundle_id'),
                    'name' => $request->validated('bundle_name'),
                    'platform' => 'IOS'
                ],
                'type' => 'bundleIds'
            ]
        ];

        $appList = Http::withToken($this->GetToken()->getData()->appstore_token)
            ->withBody(json_encode($data), 'application/json')
            ->post(self::API_URL.'/bundleIds');

        return response()->json([
            'status' => $appList->json()
        ]);
    }
}

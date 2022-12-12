<?php

namespace App\Services;

use Firebase\JWT\JWT;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

use App\Models\AppStoreConnectSetting;

class AppStoreConnectService
{
    public static string $API_URL = 'https://api.appstoreconnect.apple.com/v1';

    private readonly AppStoreConnectSetting $appStoreConnectSetting;

    public function __construct()
    {
        $this->appStoreConnectSetting = Auth::user()->workspace->appStoreConnectSetting;
    }

    public function GetClient() : PendingRequest
    {
        return Http::withToken($this->CreateToken()->getData()->appstore_token);
    }

    public function CreateToken() : JsonResponse
    {
        $payload = [
            'iss' => $this->appStoreConnectSetting->issuer_id,
            'exp' => time() + config('appstore.cache_duration') * 60,
            'aud' => 'appstoreconnect-v1'
        ];

        try
        {
            $generatedToken = JWT::encode($payload,
                $this->appStoreConnectSetting->private_key,
                'ES256',
                $this->appStoreConnectSetting->kid,
            );
        }
        catch (\Exception $exception)
        {
            return response()->json([
                'appstore_token' => null,
                'error' => "Could not generate token! Exception Code: {$exception->getCode()}"
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([ 'appstore_token' => $generatedToken ]);
    }
}

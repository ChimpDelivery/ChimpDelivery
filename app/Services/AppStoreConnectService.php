<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

use Firebase\JWT\JWT;

use App\Models\AppStoreConnectSetting;

class AppStoreConnectService
{
    public function __construct(
        private readonly ?AppStoreConnectSetting $settings = null
    ) { }

    public function GetHttpClient() : PendingRequest
    {
        return Http::withToken($this->CreateToken()->getData()->appstore_token);
    }

    public function CreateToken() : JsonResponse
    {
        try
        {
            $generatedToken = $this->PrepareTokenData();
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

    private function PrepareTokenData() : string
    {
        $settings = $this->GetSettings();

        return JWT::encode(
            payload: [
                'iss' => $settings->issuer_id,
                'exp' => time() + config('appstore.cache_duration') * 60,
                'aud' => 'appstoreconnect-v1'
            ],
            key: $settings->private_key,
            alg: 'ES256',
            keyId: $settings->kid,
        );
    }

    private function GetSettings() : AppStoreConnectSetting
    {
        return $this->settings ?? Auth::user()->workspace->appStoreConnectSetting;
    }
}

<?php

namespace App\Services;

use Firebase\JWT\JWT;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Models\AppStoreConnectSetting;

class AppStoreConnectService
{
    public static string $API_URL = 'https://api.appstoreconnect.apple.com/v1';

    private AppStoreConnectSetting $appStoreConnectSetting;

    public function __construct(Request $request)
    {
        $this->appStoreConnectSetting = $request->expectsJson()
            ? Auth::user()->appStoreConnectSetting
            : Auth::user()->workspace->appStoreConnectSetting;
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

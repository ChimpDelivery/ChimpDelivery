<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Services\AppStoreConnectService;

class CreateToken
{
    use AsAction;

    public function handle(Request $request) : JsonResponse
    {
        $appStoreConnectService = new AppStoreConnectService();

        return response()->json([
            'appstore_token' => $appStoreConnectService->CreateToken()->getData()->appstore_token
        ]);
    }
}

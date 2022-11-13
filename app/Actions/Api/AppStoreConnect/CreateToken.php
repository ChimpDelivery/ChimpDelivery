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
        return response()->json([
            'appstore_token' => app(AppStoreConnectService::class)
                ->CreateToken()
                ->getData()
                ->appstore_token
        ]);
    }
}

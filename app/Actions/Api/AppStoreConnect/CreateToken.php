<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Services\AppStoreConnectService;

class CreateToken
{
    use AsAction;

    public function handle(AppStoreConnectService $service) : JsonResponse
    {
        return $service->CreateToken();
    }
}

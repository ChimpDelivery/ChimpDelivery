<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Services\AppStoreConnectService;

class CreateToken
{
    use AsAction;

    public function handle(AppStoreConnectService $service) : JsonResponse
    {
        return $service->CreateToken();
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }
}

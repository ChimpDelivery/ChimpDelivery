<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class GetAppList
{
    use AsAction;

    public function handle() : JsonResponse
    {
        $appList = GetFullAppInfo::run();
        $data = $appList->getData()->app_list;

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

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }
}

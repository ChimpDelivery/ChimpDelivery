<?php

namespace App\Actions\Api\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GetAppList
{
    use AsAction;

    public function handle(Request $request) : JsonResponse
    {
        $appList = GetFullAppInfo::run($request);
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
}

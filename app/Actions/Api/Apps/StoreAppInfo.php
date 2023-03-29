<?php

namespace App\Actions\Api\Apps;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use App\Models\AppInfo;
use App\Events\AppChanged;
use App\Http\Requests\AppInfo\StoreAppInfoRequest;

class StoreAppInfo
{
    use AsAction;

    public function handle(StoreAppInfoRequest $request) : AppInfo
    {
        $appModel = $request->user()->workspace->apps()
            ->where('app_bundle', '=', $request->validated('app_bundle'))
            ->firstOrNew()
            ->fill($request->safe()->all());

        if ($appModel->save())
        {
            event(new AppChanged($appModel, $request));
        }

        return $appModel;
    }

    public function htmlResponse(AppInfo $appInfo) : RedirectResponse
    {
        $message = implode(' ', [
            "Project: <b>{$appInfo->project_name}</b>",
            ($appInfo->wasRecentlyCreated) ? 'created.' : 'updated.'
        ]);

        return to_route('index')->with('success', $message);
    }

    public function jsonResponse(AppInfo $appInfo) : JsonResponse
    {
        return response()->json($appInfo);
    }

    public function authorize(StoreAppInfoRequest $request)
    {
        return $request->user()->can('create app');
    }
}

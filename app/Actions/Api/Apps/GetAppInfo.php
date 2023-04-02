<?php

namespace App\Actions\Api\Apps;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

use App\Models\AppInfo;
use App\Actions\Api\S3\GetAppIcon;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetAppInfo
{
    use AsAction;

    public function handle(AppInfo $appInfo) : AppInfo
    {
        return $appInfo->makeHidden([
            'id',
            'workspace_id',
            'project_name',
            'appstore_id',
        ]);
    }

    public function asController(GetAppInfoRequest $request) : AppInfo
    {
        return $this->handle(
            $request->user()->workspace->apps()->findOrFail($request->validated('id'))
        );
    }

    public function htmlResponse(AppInfo $appInfo) : View
    {
        return view('appinfo-form')
            ->with('appInfo', $appInfo)
            ->with('formAction', route('update_app_info', [ 'id' => $appInfo->id ]));
    }

    public function jsonResponse(AppInfo $appInfo) : JsonResponse
    {
        $appInfo->app_icon = GetAppIcon::run($appInfo);

        return response()->json($appInfo);
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        return $request->user()->can('view apps');
    }
}

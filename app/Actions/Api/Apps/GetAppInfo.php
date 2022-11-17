<?php

namespace App\Actions\Api\Apps;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetAppInfo
{
    use AsAction;

    private AppInfo $app;

    public function handle(GetAppInfoRequest $request) : AppInfo
    {
        return $this->app;
    }

    public function htmlResponse(AppInfo $appInfo) : View
    {
        return view('appinfo-form')->with('appInfo', $appInfo);
    }

    public function jsonResponse(AppInfo $appInfo) : JsonResponse
    {
        return response()->json($appInfo->makeHidden([
            'id',
            'workspace_id',
            'app_icon',
            'project_name',
            'appstore_id',
        ]));
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        $this->app = Auth::user()->workspace->apps()->findOrFail($request->validated('id'));

        return !Auth::user()->isNew() && Auth::user()->can('view apps');
    }
}

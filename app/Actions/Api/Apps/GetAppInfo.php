<?php

namespace App\Actions\Api\Apps;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

use App\Models\AppInfo;
use App\Models\Workspace;
use App\Services\UserActionResolverService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetAppInfo
{
    use AsAction;

    public function handle(GetAppInfoRequest $request) : AppInfo
    {
        return AppInfo::find($request->validated('id'), [
            'id',
            'app_name',
            'project_name',
            'app_bundle',
            'appstore_id',
            'fb_app_id',
            'fb_client_token',
            'ga_id',
            'ga_secret',
        ]);
    }

    public function htmlResponse(AppInfo $appInfo) : View
    {
        return view('appinfo-form')->with('appInfo', $appInfo);
    }

    public function jsonResponse(AppInfo $appInfo) : JsonResponse
    {
        return response()->json($appInfo->makeHidden([
            'id',
            'project_name',
            'appstore_id',
        ]));
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        $userResolver = new UserActionResolverService('view apps');
        $workspaceId = $userResolver->workspaceId;
        $appWorkspaceId = AppInfo::find($request->validated('id'))->workspace->id;

        return $userResolver->isAllowed
            && $appWorkspaceId === $workspaceId
            && $workspaceId !== Workspace::$DEFAULT_WORKSPACE_ID;
    }
}

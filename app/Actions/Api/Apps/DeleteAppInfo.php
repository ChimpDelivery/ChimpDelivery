<?php

namespace App\Actions\Api\Apps;

use App\Services\ActionUserType;
use App\Services\UserActionResolverService;
use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Models\Workspace;
use App\Traits\AsActionResponse;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class DeleteAppInfo
{
    use AsAction;
    use AsActionResponse;

    public function handle(GetAppInfoRequest $request) : array
    {
        $app = AppInfo::find($request->validated('id'));
        $isAppDeleted = $app->delete();

        $message = $isAppDeleted
            ? "Project: <b>{$app->project_name}</b> deleted."
            : "Project: <b>{$app->project_name}</b> could not deleted!";

        return [
            'success' => $isAppDeleted,
            'message' => $message,
            'redirect' => 'index',
        ];
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        $userResolver = new UserActionResolverService('delete app');
        $workspaceId = $userResolver->workspaceId;
        $appWorkspaceId = AppInfo::find($request->validated('id'))->workspace->id;

        return $userResolver->isAllowed
            && $appWorkspaceId === $workspaceId
            && $workspaceId !== Workspace::$DEFAULT_WORKSPACE_ID;
    }
}

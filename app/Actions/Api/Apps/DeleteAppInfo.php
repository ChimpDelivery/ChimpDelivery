<?php

namespace App\Actions\Api\Apps;

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
        $appWorkspaceId = AppInfo::find($request->validated('id'))->workspace->id;
        $userWorkspaceId = Auth::user()->workspace->id;

        return $appWorkspaceId === $userWorkspaceId
            && $userWorkspaceId !== Workspace::$DEFAULT_WORKSPACE_ID
            && Auth::user()->can('delete app');
    }
}

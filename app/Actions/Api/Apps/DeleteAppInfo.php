<?php

namespace App\Actions\Api\Apps;

use Lorisleiva\Actions\Concerns\AsAction;

use App\Models\AppInfo;
use App\Traits\AsActionResponse;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class DeleteAppInfo
{
    use AsAction;
    use AsActionResponse;

    public function handle(AppInfo $appInfo) : array
    {
        $isAppDeleted = $appInfo->delete();

        $message = $isAppDeleted
            ? "Project: <b>{$appInfo->project_name}</b> deleted."
            : "Project: <b>{$appInfo->project_name}</b> could not deleted!";

        return [
            'success' => $isAppDeleted,
            'message' => $message,
            'redirect' => 'index',
        ];
    }

    public function asController(GetAppInfoRequest $request) : array
    {
        return $this->handle(
            $request->user()->workspace->apps()->findOrFail($request->validated('id'))
        );
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        return $request->user()->can('delete app');
    }
}

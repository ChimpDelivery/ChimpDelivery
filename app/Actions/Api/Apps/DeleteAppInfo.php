<?php

namespace App\Actions\Api\Apps;

use Lorisleiva\Actions\Concerns\AsAction;

use App\Traits\AsActionResponse;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class DeleteAppInfo
{
    use AsAction;
    use AsActionResponse;

    public function handle(GetAppInfoRequest $request) : array
    {
        $app = $request->user()->workspace->apps()->findOrFail($request->validated('id'));

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
        return $request->user()->can('delete app');
    }
}

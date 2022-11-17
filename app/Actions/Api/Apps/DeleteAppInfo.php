<?php

namespace App\Actions\Api\Apps;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Traits\AsActionResponse;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class DeleteAppInfo
{
    use AsAction;
    use AsActionResponse;

    private AppInfo $app;

    public function handle(GetAppInfoRequest $request) : array
    {
        $isAppDeleted = $this->app->delete();

        $message = $isAppDeleted
            ? "Project: <b>{$this->app->project_name}</b> deleted."
            : "Project: <b>{$this->app->project_name}</b> could not deleted!";

        return [
            'success' => $isAppDeleted,
            'message' => $message,
            'redirect' => 'index',
        ];
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        $this->app = Auth::user()->workspace->apps()->findOrFail($request->validated('id'));

        return !Auth::user()->isNew() && Auth::user()->can('delete app');
    }
}

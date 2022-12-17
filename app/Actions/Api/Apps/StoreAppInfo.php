<?php

namespace App\Actions\Api\Apps;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Events\AppChanged;
use App\Http\Requests\AppInfo\StoreAppInfoRequest;

class StoreAppInfo
{
    use AsAction;

    private bool $isModelRestored = false;

    public function handle(StoreAppInfoRequest $request) : AppInfo
    {
        $appModel = Auth::user()->workspace->apps()
            ->withTrashed()
            ->where('app_bundle', '=', $request->validated('app_bundle'))
            ->firstOrNew();

        if ($appModel->trashed())
        {
            $this->isModelRestored = true;
            $appModel->restore();
        }

        $appModel->fill($request->safe()->except([
            'workspace_id',
            'app_icon',
        ]));

        $appModel->save();

        event(new AppChanged($appModel, $request));

        return $appModel;
    }

    public function htmlResponse(AppInfo $appInfo) : RedirectResponse
    {
        $message = ($appInfo->wasRecentlyCreated || $this->isModelRestored)
            ? "Project: <b>{$appInfo->project_name}</b> created."
            : "Project: <b>{$appInfo->project_name}</b> updated.";

        return to_route('index')->with('success', $message);
    }

    public function jsonResponse(AppInfo $appInfo) : JsonResponse
    {
        return response()->json($appInfo);
    }

    public function authorize()
    {
        return Auth::user()->can('create app');
    }
}

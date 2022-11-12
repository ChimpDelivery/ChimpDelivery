<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\AppInfo\GetAppInfoRequest;
use App\Http\Requests\AppInfo\StoreAppInfoRequest;
use App\Http\Requests\AppInfo\UpdateAppInfoRequest;

use App\Models\AppInfo;
use App\Actions\Files\UploadAppIcon;

class AppInfoController extends Controller
{
    public function CreateApp(StoreAppInfoRequest $request) : JsonResponse
    {
        $this->authorize('create', AppInfo::class);

        // prepare model
        $appModel = AppInfo::withTrashed()
            ->where('app_bundle', '=', $request->validated('app_bundle'))
            ->firstOrNew();

        // populate model
        $this->RestoreOrCreate($appModel, $request);

        return response()->json([ 'app' => $appModel ], Response::HTTP_OK);
    }

    public function UpdateApp(UpdateAppInfoRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));

        $this->authorize('update', $app);

        $app->update($request->safe()->except([ 'id', 'project_name' ]));

        return response()->json($app, Response::HTTP_OK);
    }

    public function DeleteApp(GetAppInfoRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));

        $this->authorize('delete', $app);

        $app->delete();

        return response()->json(['message' => "Project: <b>{$app->project_name}</b> deleted."], Response::HTTP_OK);
    }

    private function RestoreOrCreate(AppInfo $appModel, StoreAppInfoRequest $request)
    {
        if ($appModel->trashed())
        {
            $appModel->restore();
        }

        $appModel->fill([ 'workspace_id' => Auth::user()->workspace_id ]);
        $appModel->fill($request->safe()->all());

        if ($request->hasFile('app_icon'))
        {
            $appModel->app_icon = UploadAppIcon::run($request->safe()->app_icon);
        }

        $appModel->save();
    }
}

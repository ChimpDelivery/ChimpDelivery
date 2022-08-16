<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfo\GetAppInfoRequest;
use App\Http\Requests\AppInfo\StoreAppInfoRequest;
use App\Http\Requests\AppInfo\UpdateAppInfoRequest;

use App\Models\AppInfo;
use App\Models\File;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;

class AppInfoController extends Controller
{
    public function GetApp(GetAppInfoRequest $request) : JsonResponse
    {
        $response = AppInfo::find($request->validated('id'), [
            'app_bundle',
            'app_name',
            'fb_app_id',
            'ga_id',
            'ga_secret'
        ]);

        return response()->json($response, Response::HTTP_OK);
    }

    public function CreateApp(StoreAppInfoRequest $request) : JsonResponse
    {
        // prepare model
        $appModel = AppInfo::withTrashed()
            ->where('app_bundle', '=', $request->validated('app_bundle'))
            ->firstOrNew();

        // populate model
        $this->RestoreOrCreate($appModel, $request);

        $githubResponse = app(GithubController::class)->CreateRepository($request)->getData();

        return response()->json([
            'app' => $appModel,
            'git' => $githubResponse
        ], Response::HTTP_OK);
    }

    public function UpdateApp(UpdateAppInfoRequest $request) : JsonResponse
    {
        $selectedApp = AppInfo::find($request->validated('id'));
        $selectedApp->update($request->all());

        return response()->json($selectedApp, Response::HTTP_OK);
    }

    public function DeleteApp(GetAppInfoRequest $request) : JsonResponse
    {
        $appInfo = AppInfo::find($request->validated('id'));
        $appInfo->delete();

        return response()->json(['message' => "Project: <b>{$appInfo->project_name}</b> deleted."], Response::HTTP_OK);
    }

    private function RestoreOrCreate(AppInfo $appModel, StoreAppInfoRequest $request)
    {
        $request = $request->merge([ 'workspace_id' => Auth::user()->workspace->id ]);

        if ($appModel->trashed()) {
            $appModel->restore();
        }

        $appModel->fill($request->all());

        if ($request->hasFile('app_icon')) {
            $appModel->app_icon = $this->GenerateHashAndUpload($request->file('app_icon'));
        }

        $appModel->save();
    }

    // todo: move to service class
    private function GenerateHashAndUpload($iconImage) : string
    {
        $hash = md5_file($iconImage);
        $iconFile = File::where('hash', $hash)->firstOrNew();

        if (!$iconFile->exists)
        {
            $fileName = pathinfo($iconImage->getClientOriginalName(), PATHINFO_FILENAME);
            $filePath = time() . '-' . $fileName . '.' . $iconImage->getClientOriginalExtension();

            $iconFile->update(['path' => $filePath, 'hash' => $hash]);
            $iconFile->save();

            $iconImage->move(public_path('images/app-icons'), $iconFile->path);
            return $iconFile->path;
        }

        return $iconFile->path;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfo\StoreAppInfoRequest;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

use App\Models\AppInfo;
use App\Models\File;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AppInfoController extends Controller
{
    public function GetApp(GetAppInfoRequest $request) : JsonResponse
    {
        $response = AppInfo::find($request->id, [
            'app_bundle',
            'app_name',
            'fb_app_id',
            'ga_id',
            'ga_secret'
        ]);

        return response()->json($response, Response::HTTP_ACCEPTED);
    }

    public function DeleteApp(Request $request) : JsonResponse
    {
        $appInfo = AppInfo::find($request->id);

        if ($appInfo)
        {
            $appInfo->delete();
            return response()->json(['message' => "App: {$appInfo->app_name} deleted."],
                Response::HTTP_ACCEPTED);
        }

        return response()->json(['message' => 'App not found!'],
            Response::HTTP_FORBIDDEN);
    }

    // todo: refactor mass-assignment
    public function PopulateAppData(StoreAppInfoRequest $request, AppInfo $appInfo) : void
    {
        if ($appInfo->trashed())
        {
            $appInfo->restore();
        }

        // we can't update app_name, app_bundle and appstore_id in created apps.
        if (!$appInfo->exists)
        {
            $appInfo->app_name = $request->app_name;
            $appInfo->app_bundle = $request->app_bundle;
            $appInfo->appstore_id = $request->appstore_id;
        }

        $appInfo->project_name = $request->project_name;

        if ($request->hasFile('app_icon'))
        {
            $appInfo->app_icon = $this->GenerateHashAndUpload($request->file('app_icon'));
        }

        if (!empty($request->fb_app_id)) { $appInfo->fb_app_id = $request->fb_app_id; }
        if (!empty($request->ga_id)) { $appInfo->ga_id = $request->ga_id; }
        if (!empty($request->ga_secret)) { $appInfo->ga_secret = $request->ga_secret; }

        $appInfo->save();
    }

    // todo: move to service class
    private function GenerateHashAndUpload($iconImage) : string
    {
        $hash = md5_file($iconImage);
        $iconFile = File::where('hash', $hash)->first();

        if (!$iconFile)
        {
            $fileName = pathinfo($iconImage->getClientOriginalName(), PATHINFO_FILENAME);

            $iconFile = new File();
            $iconFile->path = time() . "-" . $fileName . "." . $iconImage->getClientOriginalExtension();
            $iconFile->hash = md5_file($iconImage);
            $iconFile->save();

            $iconImage->move(public_path('images/app-icons'), $iconFile->path);
            return $iconFile->path;
        }

        return $iconFile->path;
    }
}

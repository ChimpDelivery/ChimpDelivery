<?php

namespace App\Actions\Api\Ftp;

use App\Models\AppInfo;
use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Workspace;
use App\Services\FtpService;
use App\Traits\AsActionResponse;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

/// TalusStudio specific action.
/// Add FB ID to app-ads.txt in talusstudio.com/app-ads.txt
/// Required for post-publishing on Facebook Developer
class CreateFBAppAds
{
    use AsAction;
    use AsActionResponse;

    public function handle(?GetAppInfoRequest $request, ?AppInfo $appInfo = null) : array
    {
        $ftpService = app(FtpService::class);

        $appAds = Storage::disk('ftp')->get(config('facebook.app-ads.file'));
        if (!$appAds)
        {
            return [
                'success' => false,
                'message' => "app-ads.txt could not found!
                    Expected path: {$ftpService->GetDomain()}/" . config('facebook.app-ads.file'),
            ];
        }

        //
        $app = $appInfo ?? Auth::user()->workspace->apps()->findOrFail($request->validated('id'));

        if (str_contains($appAds, $app->fb_app_id))
        {
            return [
                'success' => false,
                'message' => "FB App ID: <b>{$app->fb_app_id}</b> already in <b>app-ads.txt</b> list!"
            ];
        }
        //
        $data = implode(', ', [
            config('facebook.app-ads.domain'),
            $app->fb_app_id,
            config('facebook.app-ads.type'),
            config('facebook.app-ads.cert-authority-id')
        ]);

        $uploadedFile = Storage::disk('ftp')->append(
            config('facebook.app-ads.file'),
            $data
        );

        return [
            'success' => $uploadedFile,
            'message' => "FB App ID initialized in app-ads.txt!",
        ];
    }

    public function authorize(GetAppInfoRequest $request) : bool
    {
        return Auth::user()->workspace->id === Workspace::INTERNAL_WS_ID;
    }
}

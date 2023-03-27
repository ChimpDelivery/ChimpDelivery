<?php

namespace App\Actions\Api\Ftp;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\FtpService;
use App\Traits\AsActionResponse;

/// TalusStudio specific action.
/// Add FB ID to app-ads.txt in talusstudio.com/app-ads.txt
/// Required for post-publishing on Facebook Developer
class CreateFBAppAds
{
    use AsAction;
    use AsActionResponse;

    public function __construct(
        private readonly FtpService $ftpService
    ) { }

    public function handle(AppInfo $app) : array
    {
        if (empty($app->fb_app_id))
        {
            return [
                'success' => false,
                'message' => 'FB App ID is <b>empty</b>!'
            ];
        }

        $ftpClient = $this->ftpService->GetDisk();

        $appAds = $ftpClient->get(config('facebook.app-ads.file'));
        if (!$appAds)
        {
            return [
                'success' => false,
                'message' => "app-ads.txt could not found! Expected path: {$this->ftpService->domain}/" . config('facebook.app-ads.file'),
            ];
        }

        //
        if (str_contains($appAds, $app->fb_app_id))
        {
            return [
                'success' => false,
                'message' => "FB App ID: <b>{$app->fb_app_id}</b> already in <b>app-ads.txt</b> file!"
            ];
        }
        //
        $data = implode(', ', [
            config('facebook.app-ads.domain'),
            $app->fb_app_id,
            config('facebook.app-ads.type'),
            config('facebook.app-ads.cert-authority-id')
        ]);

        $uploadedFile = $ftpClient->append(config('facebook.app-ads.file'), $data);

        return [
            'success' => $uploadedFile,
            'message' => "FB App ID: <b>{$app->fb_app_id}</b> initialized in <b>app-ads.txt</b> file.",
        ];
    }

    public function authorize() : bool
    {
        return Auth::user()->isInternal();
    }
}

<?php

namespace App\Actions\Api\Ftp;

use App\Models\AppInfo;
use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Workspace;
use App\Services\FtpService;
use App\Traits\AsActionResponse;

/// TalusStudio specific action.
/// Creates Privacy2 File that required by Google Play Console for app review.
class CreateAppPrivacy
{
    use AsAction;
    use AsActionResponse;

    public function handle(AppInfo $appInfo) : array
    {
        $ftpService = app(FtpService::class);
        $privacy = Storage::disk('ftp')->get(config('googleplay.privacy.template_file'));

        if (!$privacy)
        {
            return [
                'success' => false,
                'message' => "Template Privacy file could not found!
                    Expected path: {$ftpService->GetDomain()}/" . config('googleplay.privacy.template_file'),
            ];
        }

        $newFilePath = implode('/', [
            config('googleplay.privacy.container_folder'),
            Str::slug($appInfo->app_name),
            config('googleplay.privacy.file_name')
        ]);
        $privacyUrl = "{$ftpService->GetDomain()}/{$newFilePath}";
        $privacyLink = "<a href={$privacyUrl}>{$privacyUrl}</a>";

        if (Storage::disk('ftp')->exists($newFilePath))
        {
            return [
                'success' => false,
                'message' => "Privacy already exists! Check: {$privacyLink}",
            ];
        }

        $updatedContent = str_replace(config('googleplay.privacy.search'), $appInfo->app_name, $privacy);
        $uploadedFile = Storage::disk('ftp')->put($newFilePath, $updatedContent);

        return [
            'success' => $uploadedFile,
            'message' => "Privacy created! <b>Link:</b> {$privacyLink}",
        ];
    }

    public function authorize() : bool
    {
        return Auth::user()->isInternal();
    }
}

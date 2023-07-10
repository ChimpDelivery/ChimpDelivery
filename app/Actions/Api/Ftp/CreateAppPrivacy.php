<?php

namespace App\Actions\Api\Ftp;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\FtpService;
use App\Traits\AsActionResponse;

/// Internal Studio specific action.
/// Creates Privacy2 File that required by Google Play Console for app review.
class CreateAppPrivacy
{
    use AsAction;
    use AsActionResponse;

    public function __construct(
        private readonly FtpService $ftpService
    ) {
    }

    public function handle(AppInfo $appInfo) : array
    {
        $ftpClient = $this->ftpService->GetDisk();

        $privacy = $ftpClient->get(config('googleplay.privacy.template_file'));

        if (!$privacy)
        {
            return [
                'success' => false,
                'message' => "Template Privacy file could not found! Expected path: {$this->ftpService->domain}/" . config('googleplay.privacy.template_file'),
            ];
        }

        $newFilePath = implode('/', [
            config('googleplay.privacy.container_folder'),
            Str::slug($appInfo->app_name),
            config('googleplay.privacy.file_name'),
        ]);
        $privacyUrl = "{$this->ftpService->domain}/{$newFilePath}";
        $privacyLink = "<a href={$privacyUrl}>{$privacyUrl}</a>";

        if ($ftpClient->exists($newFilePath))
        {
            return [
                'success' => false,
                'message' => "Privacy already exists! Check: {$privacyLink}",
            ];
        }

        $updatedContent = str_replace(config('googleplay.privacy.search'), $appInfo->app_name, $privacy);
        $uploadedFile = $ftpClient->put($newFilePath, $updatedContent);

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

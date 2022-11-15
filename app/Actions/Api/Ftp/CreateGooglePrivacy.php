<?php

namespace App\Actions\Api\Ftp;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Storage;

use App\Models\AppInfo;
use App\Traits\AsActionResponse;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class CreateGooglePrivacy
{
    use AsAction;
    use AsActionResponse;

    private string $search = "___APP___";

    public function handle(GetAppInfoRequest $request) : array
    {
        $privacy = Storage::disk('ftp')->get('privacy_template/privacy.html');
        if (!$privacy) {
            return [
                'success' => false,
                'message' => 'Template Privacy file could not found!',
            ];
        }

        $appInfo = AppInfo::find($request->validated('id'));
        $newFilePath = "{$appInfo->app_name}/privacy.html";
        $privacyLink = "http://www.talusstudio.com/{$newFilePath}";

        if (Storage::disk('ftp')->exists($newFilePath))
        {
            return [
                'success' => false,
                'message' => "Privacy already exists! Check: {$privacyLink}",
            ];
        }

        $updatedContent = str_replace($this->search, $appInfo->app_name, $privacy);
        $uploadedFile = Storage::disk('ftp')->put($newFilePath, $updatedContent);

        return [
            'success' => $uploadedFile,
            'message' => "Privacy created! <b>Link:</b> <a href='".$privacyLink."'>$privacyLink</a>",
        ];
    }
}

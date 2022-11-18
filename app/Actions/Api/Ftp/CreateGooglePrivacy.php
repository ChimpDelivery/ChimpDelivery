<?php

namespace App\Actions\Api\Ftp;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\AppInfo;
use App\Models\Workspace;
use App\Traits\AsActionResponse;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

/// talus specific action
class CreateGooglePrivacy
{
    use AsAction;
    use AsActionResponse;

    private const PrivacyFileName = 'privacy.html';

    private string $search = "___APP___";

    private string $privacyHolderUrl = 'http://www.talusstudio.com';
    private string $privacyTemplatePath = 'privacy_template/' . self::PrivacyFileName;

    public function handle(GetAppInfoRequest $request) : array
    {
        $privacy = Storage::disk('ftp')->get($this->privacyTemplatePath);
        if (!$privacy) {
            return [
                'success' => false,
                'message' => "Template Privacy file could not found!
                    Expected path: {$this->privacyHolderUrl}/{$this->privacyTemplatePath}",
            ];
        }

        $appInfo = Auth::user()->workspace->apps()->findOrFail($request->validated('id'));
        $newFilePath = $appInfo->app_name . '/' . self::PrivacyFileName;
        $privacyLink = "{$this->privacyHolderUrl}/{$newFilePath}";

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

    public function authorize(GetAppInfoRequest $request) : bool
    {
        return Auth::user()->workspace->id === Workspace::INTERNAL_WS_ID;
    }
}

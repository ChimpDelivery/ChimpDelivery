<?php

namespace App\Actions\Api\Ftp;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Workspace;
use App\Traits\AsActionResponse;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

/// TalusStudio specific action.
/// Creates Privacy2 File that required by Google Play Console for app review.
class CreateGooglePrivacy
{
    use AsAction;
    use AsActionResponse;

    private const PrivacyContainerFolder = 'hc';
    private const PrivacyTemplateFile = 'privacy_template/privacy.html';
    private const PrivacyFileName = 'privacy.html';

    private string $search = "___APP___";

    public function handle(GetAppInfoRequest $request) : array
    {
        // parse ftp url for future changes
        $ftpDomain = Str::of(config('filesystems.disks.ftp.host'))
            ->explode('.')
            ->slice(1)
            ->prepend('http://www')
            ->implode('.');

        $privacy = Storage::disk('ftp')->get(self::PrivacyTemplateFile);

        if (!$privacy)
        {
            return [
                'success' => false,
                'message' => "Template Privacy file could not found!
                    Expected path: {$ftpDomain}/" . self::PrivacyTemplateFile,
            ];
        }

        $appInfo = Auth::user()->workspace->apps()->findOrFail($request->validated('id'));
        $newFilePath = implode('/', [
            self::PrivacyContainerFolder,
            $appInfo->app_name,
            self::PrivacyFileName
        ]);
        $privacyLink = "{$ftpDomain}/{$newFilePath}";

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

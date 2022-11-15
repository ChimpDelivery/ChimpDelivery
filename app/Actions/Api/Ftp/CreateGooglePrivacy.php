<?php

namespace App\Actions\Api\Ftp;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Storage;

use App\Models\AppInfo;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class CreateGooglePrivacy
{
    use AsAction;

    private string $search = "___APP___";

    public function handle(GetAppInfoRequest $request)
    {
        $privacy = Storage::disk('ftp')->get('privacy_template/privacy.html');
        if (!$privacy) {
            return response()->json([
                'success' => false,
                'message' => 'Template Privacy file could not found!'
            ]);
        }

        $appInfo = AppInfo::find($request->validated('id'));
        $newFilePath = "{$appInfo->app_name}/privacy.html";

        if (Storage::disk('ftp')->exists($newFilePath))
        {
            return response()->json([
                'success' => false,
                'message' => 'Privacy already exists!'
            ]);
        }

        $updatedContent = str_replace($this->search, $appInfo->app_name, $privacy);
        $uploadedFile = Storage::disk('ftp')->put($newFilePath, $updatedContent);

        return response()->json($uploadedFile);
    }
}

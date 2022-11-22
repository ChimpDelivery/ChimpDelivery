<?php

namespace App\Actions\Files;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\UploadedFile;

use App\Models\File;

class UploadAppIcon
{
    use AsAction;

    public function handle(UploadedFile $iconImage) : string
    {
        // hash used for file path
        $hash = hash_file('sha256', $iconImage);
        $iconFileModel = File::firstOrNew([ 'hash' => $hash ]);

        if (!$iconFileModel->exists)
        {
            $iconFileModel->fill([
                'path' => $hash,
                'hash' => $hash,
            ])->save();

            $iconImage->storePubliclyAs('app-icons', $hash);
            return $iconFileModel->path;
        }

        return $hash;
    }
}

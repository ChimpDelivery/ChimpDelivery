<?php

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class FileValidator
{
    public function __construct(
        private readonly Validator $validator,
        private readonly Request $request
    ) { }

    public function ValidateFile($fileInputName, $serverMime, $clientMime, $clientExt) : void
    {
        if ($this->request->hasFile($fileInputName))
        {
            $isValidFile = $this->IsValidFile(
                file: $this->request->validated($fileInputName),
                serverMime: $serverMime,
                clientMime: $clientMime,
                clientExt: $clientExt,
            );

            if (!$isValidFile)
            {
                $this->validator->errors()->add(
                    $fileInputName,
                    "Invalid {$fileInputName} type! Only {$clientExt} files allowed!"
                );
            }
        }
    }

    private function IsValidFile($file, $serverMime, $clientMime, $clientExt) : bool
    {
        return $file->getMimeType() === $serverMime
            && $file->getClientMimeType() === $clientMime
            && $this->IsValidExtension($file, $clientExt);
    }

    private function IsValidExtension($file, $extension) : bool
    {
        return Str::of($file->getClientOriginalName())->endsWith($extension);
    }
}

<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class S3Service
{
    // workspaces have their own folders on s3 bucket to store required assets
    // workspace folder contains sub-folders by file-types
    private readonly string $workspaceFolder;

    public function __construct()
    {
        $this->workspaceFolder = implode('/', [
            config('aws.s3.ws_path'),
            Auth::user()->workspace->id,
        ]);
    }

    public function IsFileExists($path) : bool
    {
        return Storage::disk('s3')->exists($this->CreateScopedPath($path));
    }

    // get files from only related workspace
    public function GetFile(string $path) : ?string
    {
        return Storage::disk('s3')->get($this->CreateScopedPath($path));
    }

    // warning: this function return temporary url when file doesn't exist
    public function GetFileLink(string $path) : string
    {
        return Storage::disk('s3')->temporaryUrl(
            $this->CreateScopedPath($path),
            now()->addMinutes(2)
        );
    }

    public function GetFileResponse(string $path, string $fileName, string $mimeType) : Response
    {
        $headers = [
            'Cache-Control' => 'public',
            'Content-Type' => $mimeType,
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$fileName}",
            config('aws.s3.filename-header-key') => $fileName,
        ];

        $file = $this->GetFile($path);

        return \Response::make(
            content: $file ?? 'Error: File exist in database but could not found in S3 Bucket!',
            status: $file ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY,
            headers: $headers
        );
    }

    // The files are stored according to their file types
    public function UploadFile($file) : false|string
    {
        // full path on s3
        // @example: S3Bucket/TalusDashboard_Root/{DashboardAppEnv}/Workspaces/{Id}/bin/example.bin
        $uploadedFile = Storage::disk('s3')->putFileAs(
            path: $this->CreateScopedPath($file->extension()),
            file: $file,
            name: $file->hashName()
        );

        // return trimmed path
        return (!$uploadedFile) ? false : "{$file->extension()}/{$file->hashName()}";
    }

    private function CreateScopedPath(string $path) : string
    {
        return "{$this->workspaceFolder}/{$path}";
    }
}

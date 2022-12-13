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

    // get files from only related workspace
    public function GetFile(string $path)
    {
        return Storage::disk('s3')->get(path: "{$this->workspaceFolder}/{$path}");
    }

    public function GetFileLink(string $path) : string
    {
        return Storage::disk('s3')->url(path: "{$this->workspaceFolder}/{$path}");
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
            content: $file,
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
            path: "{$this->workspaceFolder}/{$file->extension()}",
            file: $file,
            name: $file->hashName()
        );

        // return trimmed path
        return (!$uploadedFile) ? false : "{$file->extension()}/{$file->hashName()}";
    }
}

<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class S3Service
{
    private array $configs = [
        // s3 bucket root
        'base_path' => 'TalusDashboard_Root',

        // indicates custom-header contains filename
        'filename-header-key' => 'Dashboard-File-Name'
    ];

    // every workspace has own folder on bucket to store required assets
    private readonly string $workspaceFolder;

    public function __construct()
    {
        $this->workspaceFolder = implode('/', [
            $this->configs['base_path'],
            config('app.env'),
            'Workspaces',
            Auth::user()->workspace->id,
        ]);
    }

    public function GetWorkspaceFolder() : string
    {
        return $this->workspaceFolder;
    }

    public function GetFile(string $path)
    {
        $fullPath = $this->workspaceFolder . $path;

        return Storage::disk('s3')->get($fullPath);
    }

    public function GetFileLink(string $path) : string
    {
        return Storage::disk('s3')->url($path);
    }

    public function GetFileResponse(string $path, string $fileName, string $mimeType) : Response
    {
        $headers = [
            'Cache-Control' => 'public',
            'Content-Type' => $mimeType,
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$fileName}",
            $this->configs['filename-header-key'] => $fileName,
        ];

        $file = $this->GetFile($path);

        return \Response::make(
            content: $file,
            status: $file ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY,
            headers: $headers
        );
    }

    public function UploadProvision(string $provisionName, $provision) : false|string
    {
        return $this->UploadFile(
            $provisionName,
            $provision,
            "{$this->GetWorkspaceFolder()}/provisions"
        );
    }

    public function UploadCert(string $certName, $cert) : false|string
    {
        return $this->UploadFile(
            $certName,
            $cert,
            "{$this->GetWorkspaceFolder()}/certs"
        );
    }

    public function UploadFile(string $fileName, $file, string $path) : false|string
    {
        return Storage::disk('s3')->putFileAs($path, $file, $fileName);
    }
}

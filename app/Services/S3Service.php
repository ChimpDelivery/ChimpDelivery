<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class S3Service
{
    private const BASE_PATH = 'TalusDashboard_Root';

    private readonly string $workspaceFolder;

    public function __construct()
    {
        $this->workspaceFolder = implode('/', [
            self::BASE_PATH,
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
        return Storage::disk('s3')->get($path);
    }

    public function GetFileResponse(string $path, string $fileName, string $mimeType) : Response
    {
        $file = $this->GetFile($path);

        $headers = [
            'Cache-Control' => 'public',
            'Content-Type' => $mimeType,
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        return \Response::make($file, 200, $headers);
    }

    public function GetFileLink(string $path) : string
    {
        return Storage::disk('s3')->url($path);
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

    private function UploadFile(string $fileName, $file, string $path) : false|string
    {
        return Storage::disk('s3')->putFileAs($path, $file, $fileName);
    }
}

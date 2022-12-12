<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class S3Service
{
    // indicates custom request header for responses that contains file (provision or cert)
    private const FILE_RESPONSE_KEY = 'Dashboard-File-Name';

    // s3 service root path
    private const BASE_PATH = 'TalusDashboard_Root';

    // every workspace has own folder on bucket to store required assets
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
        return Storage::disk('s3')->get($this->workspaceFolder . $path);
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
            self::FILE_RESPONSE_KEY => $fileName,
        ];

        return \Response::make($this->GetFile($path), Response::HTTP_OK, $headers);
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

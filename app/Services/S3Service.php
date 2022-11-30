<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class S3Service
{
    private const BASE_PATH = 'TalusDashboard_Root';

    private string $workspaceFolder;

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

    public function GetFileLink(string $path) : string
    {
        return Storage::disk('s3')->url($path);
    }

    public function UploadProvision($provisionName, $provision) : false|string
    {
        return $this->UploadFile($provisionName, $provision, $this->GetWorkspaceFolder() . "/provisions");
    }

    public function UploadCert($certName, $cert) : false|string
    {
        return $this->UploadFile($certName, $cert, $this->GetWorkspaceFolder() . "/certs");
    }

    private function UploadFile($fileName, $file, $path) : false|string
    {
        return Storage::disk('s3')->putFileAs($path, $file, $fileName,);
    }
}

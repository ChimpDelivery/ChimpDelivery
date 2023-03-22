<?php

namespace App\Traits;

use Illuminate\Http\Response;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

use App\Services\S3Service;

trait AsS3Client
{
    public function UploadToS3($file) : false|string
    {
        $s3 = App::makeWith(S3Service::class, [ 'workspace' => ($this->user ?? Auth::user())->workspace ]);
        return $s3->UploadFile($file);
    }

    public function DownloadFromS3(string $srcFilePath, string $destFileName, string $mimeType) : Response
    {
        $s3 = App::makeWith(S3Service::class, [ 'workspace' => ($this->user ?? Auth::user())->workspace ]);
        return $s3->GetFileResponse($srcFilePath, $destFileName, $mimeType);
    }
}

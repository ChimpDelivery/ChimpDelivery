<?php

namespace App\Traits;

use Illuminate\Http\Response;

use App\Services\S3Service;

trait AsS3Client
{
    public function UploadToS3($file) : false|string
    {
        return app(S3Service::class)->UploadFile($file);
    }

    public function DownloadFromS3(string $srcFilePath, string $destFileName, string $mimeType) : Response
    {
        return app(S3Service::class)->GetFileResponse(
            $srcFilePath,
            $destFileName,
            $mimeType
        );
    }
}

<?php

namespace App\Traits;

use Illuminate\Http\Response;

use App\Services\S3Service;

trait AsS3Downloader
{
    public function DownloadFromS3(string $srcFilePath, string $destFileName, string $mimeType) : Response
    {
        return app(S3Service::class)->GetFileResponse(
            $srcFilePath,
            $destFileName,
            $mimeType
        );
    }
}

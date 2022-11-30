<?php

namespace App\Actions\Api\AppStoreConnect\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Services\S3Service;

class GetCertificate
{
    use AsAction;

    public function handle() : Response
    {
        return $this->DownloadAsset(Auth::user()->workspace->appstoreConnectSign->cert);
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }

    public function DownloadAsset($path) : Response
    {
        $s3Service = app(S3Service::class);
        $fileName = Str::of(Auth::user()->workspace->appstoreConnectSign->cert)
            ->explode('/')
            ->last();

        return $s3Service->GetFileResponse($path, $fileName, 'application/octet-stream');
    }
}

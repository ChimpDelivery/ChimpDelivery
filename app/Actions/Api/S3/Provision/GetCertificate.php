<?php

namespace App\Actions\Api\S3\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

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

    private function DownloadAsset(string $path) : Response
    {
        $fileName = Auth::user()->workspace->appstoreConnectSign->cert_name;

        return app(S3Service::class)->GetFileResponse($path, $fileName, 'application/x-pkcs12');
    }
}

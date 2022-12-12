<?php

namespace App\Actions\Api\S3\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Services\S3Service;

class GetCertificate
{
    use AsAction;

    public function handle(S3Service $service) : Response
    {
        return $this->DownloadAsset($service, Auth::user()->workspace->appstoreConnectSign->cert);
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }

    private function DownloadAsset(S3Service $service, string $path) : Response
    {
        $fileName = Auth::user()->workspace->appstoreConnectSign->cert_name;

        return $service->GetFileResponse($path, $fileName, 'application/x-pkcs12');
    }
}

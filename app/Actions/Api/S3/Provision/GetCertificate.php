<?php

namespace App\Actions\Api\S3\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Traits\AsS3Client;

class GetCertificate
{
    use AsAction;
    use AsS3Client;

    public function handle() : Response
    {
        $sign = Auth::user()->workspace->appstoreConnectSign;

        return empty($sign->cert)
            ? response('Error: Certificate could not found in database!', Response::HTTP_UNPROCESSABLE_ENTITY)
            : $this->DownloadFromS3(
                $sign->cert,
                $sign->cert_name,
                config('appstore-sign.certificate.mime')
            );
    }
}

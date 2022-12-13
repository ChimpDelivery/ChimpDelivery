<?php

namespace App\Actions\Api\S3\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Traits\AsS3Downloader;

class GetCertificate
{
    use AsAction;
    use AsS3Downloader;

    private array $configs =
    [
        'mime' => 'application/x-pkcs12',
    ];

    public function handle() : Response
    {
        $sign = Auth::user()->workspace->appstoreConnectSign;

        return empty($sign->cert)
            ? response()->noContent()
            : $this->DownloadFromS3($sign->cert, $sign->cert_name, $this->configs['mime']);
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }
}

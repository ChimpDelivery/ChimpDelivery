<?php

namespace App\Actions\Api\AppStoreConnect\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Services\S3Service;

class GetProvisionProfile
{
    use AsAction;

    public function handle() : Response
    {
        return $this->DownloadAsset(Auth::user()->workspace->appstoreConnectSign->provision_profile);
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }

    public function DownloadAsset($path) : Response
    {
        $s3Service = app(S3Service::class);
        $file = $s3Service->GetFile($path);
        $fileName = Str::of(Auth::user()->workspace->appstoreConnectSign->provision_profile)
            ->explode('/')
            ->last();

        $headers = [
            'Cache-Control' => 'public',
            'Content-Type' => 'application/octet-stream',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        return \Response::make($file, 200, $headers);
    }
}

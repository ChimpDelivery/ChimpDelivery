<?php

namespace App\Actions\Api\AppStoreConnect\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Services\S3Service;

class GetProvisionProfile
{
    // stores provision uuid data in header
    private const UUID_KEY = 'Dashboard-Provision-Profile-UUID';

    use AsAction;

    public function handle() : Response
    {
        return $this->DownloadAsset(Auth::user()->workspace->appstoreConnectSign->provision_profile);
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }

    public function DownloadAsset(string $path) : Response
    {
        $s3Service = app(S3Service::class);
        $fileName = Str::of(Auth::user()->workspace->appstoreConnectSign->provision_profile)
            ->explode('/')
            ->last();

        $response = $s3Service->GetFileResponse($path, $fileName, 'application/octet-stream');
        $response->headers->set(self::UUID_KEY, $this->GetProfileUUID($response));

        return $response;
    }

    /*
     * preg_match_all returns explodes tags from binary file,
     * example response included below
     * array:5 [
            0 => "<string>uuid-uuid-uuid-uuid-uuid</string>"
            1 => "<string>"
            2 => "string"
            3 => "uuid-uuid-uuid-uuid-uuid"
            4 => "</string>"
        ]
     */
    public function GetProfileUUID(Response $response)
    {
        $file = $response->getContent();
        preg_match_all("/(<([\w]+)[^>]*>)(.*?)(<\/\\2>)/", $file,$matches,PREG_SET_ORDER);
        $tags = collect($matches);

        // find uuid section in binary file,
        // real uuid value exist below that section
        $uuidPositionReference = $tags->filter(function ($item) {
            return Str::of($item[0])->contains('UUID');
        });

        return $tags->get($uuidPositionReference->keys()->first() + 1)[3];
    }
}

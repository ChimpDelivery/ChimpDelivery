<?php

namespace App\Actions\Api\S3\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use App\Services\S3Service;

class GetProvisionProfile
{
    use AsAction;

    // stores provision uuid data in header
    private const UUID_KEY = 'Dashboard-Provision-Profile-UUID';

    // stores team id data in header
    private const TEAM_ID_KEY = 'Dashboard-Team-ID';

    // resolved real provision profile data index
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
    private const REAL_DATA_INDEX = 3;

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
        $fileName = Auth::user()->workspace->appstoreConnectSign->provision_name;

        $response = $s3Service->GetFileResponse($path, $fileName, 'application/octet-stream');
        $response->headers->set(self::UUID_KEY, $this->GetProfileUUID($response));
        $response->headers->set(self::TEAM_ID_KEY, $this->GetTeamID($response));

        return $response;
    }

    // find uuid section, then read value below that section
    public function GetProfileUUID(Response $response)
    {
        $tags = $this->GetFileTags($response);

        $uuidPositionReference = $tags->filter(function ($tag) {
            return Str::of($tag[0])->contains('UUID');
        });
        $uuidPositionIndex = $uuidPositionReference->keys()->first();

        return $tags->get($uuidPositionIndex + 1)[self::REAL_DATA_INDEX];
    }

    // find team id section, then read value below that section
    public function GetTeamID(Response $response)
    {
        $tags = $this->GetFileTags($response);

        $teamIdPositionReference = $tags->filter(function ($tag) {
            return Str::of($tag[0])->contains('TeamIdentifier');
        });
        $teamIdPositionIndex = $teamIdPositionReference->keys()->first();

        return $tags->get($teamIdPositionIndex + 1)[self::REAL_DATA_INDEX];
    }

    private function GetFileTags(Response $response) : Collection
    {
        preg_match_all("/(<([\w]+)[^>]*>)(.*?)(<\/\\2>)/",
            $response->getContent(),
            $matches,
            PREG_SET_ORDER
        );

        return collect($matches);
    }
}

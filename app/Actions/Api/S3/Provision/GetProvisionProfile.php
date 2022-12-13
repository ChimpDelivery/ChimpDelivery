<?php

namespace App\Actions\Api\S3\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use App\Services\S3Service;

/// Reads Provision Profile(App Store Connect) file and exports uuid and team-identifier.
class GetProvisionProfile
{
    use AsAction;

    // search_tag_in_file, response_header_key
    private array $configs =
    [
        // .mobileprovision mime-type
        'mime' => 'application/octet-stream',

        // Provision Profile contains UUID (required for App Store app-signing)
        'uuid' =>
        [
            'file' => 'UUID',
            'web' => 'Dashboard-Provision-Profile-UUID',
        ],

        // Provision Profile contains Team ID (required for App Store app-signing)
        'team-id' =>
        [
            'file' => 'TeamIdentifier',
            'web' => 'Dashboard-Team-ID',
        ],

        // check GetFileTags function
        'data-index' => 3,
    ];

    public function handle() : Response
    {
        $sign = Auth::user()->workspace->appstoreConnectSign;

        return empty($sign->provision_profile)
            ? response()->noContent()
            : $this->DownloadFile($sign->provision_profile, $sign->provision_name);
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }

    private function DownloadFile(string $sourceFilePath, string $destinationFileName) : Response
    {
        $response = app(S3Service::class)->GetFileResponse(
            $sourceFilePath,
            $destinationFileName,
            $this->configs['mime']
        );

        return $this->SetProvisionFileHeaders($response);
    }

    private function SetProvisionFileHeaders(Response $fileResponse) : Response
    {
        $fileResponse->headers->set(
            key: $this->configs['uuid']['web'],
            values: $fileResponse->isOk() ? $this->GetProfileUUID($fileResponse) : ''
        );

        $fileResponse->headers->set(
            key: $this->configs['team-id']['web'],
            values: $fileResponse->isOk() ? $this->GetTeamID($fileResponse) : ''
        );

        return $fileResponse;
    }

    // find uuid section, then read value below that section
    private function GetProfileUUID(Response $response)
    {
        $tags = $this->GetFileTags($response);

        $uuidPositionReference = $tags->filter(function ($tag) {
            return str($tag[0])->contains($this->configs['uuid']['file']);
        });
        $uuidPositionIndex = $uuidPositionReference->keys()->first() + 1;

        return $tags->get($uuidPositionIndex)[$this->configs['data-index']];
    }

    // find team id section, then read value below that section
    private function GetTeamID(Response $response)
    {
        $tags = $this->GetFileTags($response);

        $teamIdPositionReference = $tags->filter(function ($tag) {
            return str($tag[0])->contains($this->configs['team-id']['file']);
        });
        $teamIdPositionIndex = $teamIdPositionReference->keys()->first() + 1;

        return $tags->get($teamIdPositionIndex)[$this->configs['data-index']];
    }

    /*
     * preg_match_all returns explodes tags from .mobileprovision file
     * array:5 [
            0 => "<string>uuid-uuid-uuid-uuid-uuid</string>"
            1 => "<string>"
            2 => "string"
            3 => "uuid-uuid-uuid-uuid-uuid"
            4 => "</string>"
        ]
     */
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

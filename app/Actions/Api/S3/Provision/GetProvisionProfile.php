<?php

namespace App\Actions\Api\S3\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use App\Services\S3Service;

/// Reads Provision Profile(App Store Connect) file and exports uuid and team-identifier.
class GetProvisionProfile
{
    use AsAction;

    // search_tag_in_file, response_header_key_on_web
    private array $configs = [
        'uuid' => [
            'file' => 'UUID',
            'web' => 'Dashboard-Provision-Profile-UUID',
        ],

        'team-id' => [
            'file' => 'TeamIdentifier',
            'web' => 'Dashboard-Team-ID',
        ],
    ];

    /*
     * preg_match_all returns explodes tags from binary file
     * array:5 [
            0 => "<string>uuid-uuid-uuid-uuid-uuid</string>"
            1 => "<string>"
            2 => "string"
            3 => "uuid-uuid-uuid-uuid-uuid"
            4 => "</string>"
        ]
     */
    private const REAL_DATA_INDEX = 3;

    public function handle(S3Service $service) : Response
    {
        $fileName = Auth::user()->workspace->appstoreConnectSign->provision_name;
        $filePath = "/provisions/{$fileName}";

        return $this->DownloadAsset($service, $filePath, $fileName);
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }

    private function DownloadAsset(
        S3Service $service,
        string $sourceFilePath,
        string $destinationFileName) : Response
    {
        $response = $service->GetFileResponse(
            $sourceFilePath,
            $destinationFileName,
            'application/octet-stream'
        );

        $response->headers->set(
            key: $this->configs['uuid']['web'],
            values: $response->isOk() ? $this->GetProfileUUID($response) : ''
        );

        $response->headers->set(
            key: $this->configs['team-id']['web'],
            values: $response->isOk() ? $this->GetTeamID($response) : ''
        );

        return $response;
    }

    // find uuid section, then read value below that section
    private function GetProfileUUID(Response $response)
    {
        $tags = $this->GetFileTags($response);

        $uuidPositionReference = $tags->filter(function ($tag) {
            return Str::of($tag[0])->contains($this->configs['uuid']['file']);
        });
        $uuidPositionIndex = $uuidPositionReference->keys()->first();

        return $tags->get($uuidPositionIndex + 1)[self::REAL_DATA_INDEX];
    }

    // find team id section, then read value below that section
    private function GetTeamID(Response $response)
    {
        $tags = $this->GetFileTags($response);

        $teamIdPositionReference = $tags->filter(function ($tag) {
            return Str::of($tag[0])->contains($this->configs['team-id']['file']);
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

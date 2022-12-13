<?php

namespace App\Actions\Api\S3\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use App\Traits\AsS3Downloader;

/// Reads Provision Profile(.mobileprovision) file
/// exports UUID, Team-Identifier, Expiration Date etc...
class GetProvisionProfile
{
    use AsAction;
    use AsS3Downloader;

    private array $configs =
    [
        // .mobileprovision mime-type
        'mime' => 'application/octet-stream',

        // required data for App Store app-signing on Jenkins Sign Stage.
        'required_tags' =>
        [
            [
                'file' => 'Name',
                'web' => 'Dashboard-Provision-Profile-Name',
            ],
            [
                'file' => 'UUID',
                'web' => 'Dashboard-Provision-Profile-UUID',
            ],
            [
                'file' => 'TeamIdentifier',
                'web' => 'Dashboard-Team-ID',
            ],
            [
                'file' => 'ExpirationDate',
                'web' => 'Dashboard-Provision-Profile-Expire',
            ],
        ],

        // check GetFileTags function
        'data-index' => 3,
    ];

    public function handle() : Response
    {
        $sign = Auth::user()->workspace->appstoreConnectSign;

        return empty($sign->provision_profile)
            ? response()->noContent()
            : $this->SetHeaders(
                $this->DownloadFromS3(
                    $sign->provision_profile,
                    $sign->provision_name,
                    $this->configs['mime']
                )
            );
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }

    // set required file headers for Provision Profile and return file
    private function SetHeaders(Response $fileResponse) : Response
    {
        foreach ($this->configs['required_tags'] as $tag)
        {
            $fileResponse->headers->set(
                key: $tag['web'],
                values: $fileResponse->isOk()
                    ? $this->GetTag($tag['file'], $fileResponse)
                    : ''
            );
        }

        return $fileResponse;
    }

    // find tag section, then read value below that section
    private function GetTag(string $tag, Response $fileResponse)
    {
        $tags = $this->GetFileTags($fileResponse);

        $tagPositionReference = $tags->filter(function($fileTag) use ($tag) {
            return str($fileTag[0])->contains("<key>$tag</key>");
        });
        $tagPositionIndex = $tagPositionReference->keys()->first() + 1;

        return $tags->get($tagPositionIndex)[$this->configs['data-index']];
    }

    // example response: https://gist.github.com/emrekovanci/b68e92d49c98e48d818c9083a8ba19c6
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

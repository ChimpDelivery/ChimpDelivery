<?php

namespace App\Actions\Api\S3\Provision;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;

use App\Models\User;
use App\Models\Workspace;
use App\Traits\AsS3Client;

/// Reads Provision Profile(.mobileprovision) file
/// exports UUID, Team-Identifier, Expiration Date etc. as a request header
class GetProvisionProfile
{
    use AsAction;
    use AsS3Client;

    // request owner
    private User $user;

    public function handle(User $user, Workspace $workspace) : Response
    {
        $this->user = $user;
        $sign = $workspace->appstoreConnectSign;

        return empty($sign->provision_profile)
            ? response('Error: Provision Profile could not found in database!', Response::HTTP_UNPROCESSABLE_ENTITY)
            : $this->SetHeaders(
                $this->DownloadFromS3(
                    $sign->provision_profile,
                    $sign->provision_name,
                    config('appstore-sign.provision.mime')
                )
            );
    }

    // set required file headers for Provision Profile and return file
    private function SetHeaders(Response $fileResponse) : Response
    {
        foreach (config('appstore-sign.provision.required_tags') as $tag)
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
    private function GetTag(string $searchedTag, Response $fileResponse)
    {
        $tags = $this->GetFileTags($fileResponse);

        $tagPositionReference = $tags->filter(
            fn($tag) => str($tag[0])->contains("<key>{$searchedTag}</key>")
        );
        $tagPositionIndex = $tagPositionReference->keys()->first() + 1;

        return $tags->get($tagPositionIndex)[config('appstore-sign.provision.data-index')];
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

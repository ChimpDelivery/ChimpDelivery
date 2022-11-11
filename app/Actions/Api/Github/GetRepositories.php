<?php

namespace App\Actions\Api\Github;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use GrahamCampbell\GitHub\Facades\GitHub;

class GetRepositories extends BaseGithubAction
{
    // http://developer.github.com/v3/repos/#list-organization-repositories
    public function handle(Request $request) : JsonResponse
    {
        $this->ResolveGithubSetting($request);
        $this->SetConnectionToken();

        $response = collect();

        try
        {
            $organizationProjects = collect(GitHub::api('repo')->org($this->githubSetting->organization_name, [
                'per_page' => config('github.item_limit'),
                'sort' => 'updated',
                'type' => 'private'
            ]));

            // custom filter added. listed git projects count can be lower than GIT_ITEM_LIMIT.
            // maybe extra organization is useful when filtering projects.

            if (!is_null($this->githubSetting->topic_name))
            {
                $organizationProjects = $organizationProjects->filter(function ($value) {
                    return in_array($this->githubSetting->topic_name, $value['topics']);
                });
            }

            $response = $organizationProjects->values()->map(function ($item) {
                return [
                    'id'   => $item['id'],
                    'name' => $item['name'],
                    'size' => round($item['size'] / 1024, 2) . 'mb'
                ];
            });
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'response' => $response ], $exception->getCode());
        }

        return response()->json([ 'response' => $response ], Response::HTTP_OK);
    }
}

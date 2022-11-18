<?php

namespace App\Actions\Api\Github;

use GrahamCampbell\GitHub\Facades\GitHub;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Services\GitHubService;

class GetRepositories
{
    use AsAction;

    // http://developer.github.com/v3/repos/#list-organization-repositories
    public function handle() : JsonResponse
    {
        $response = collect();

        try
        {
            $githubSetting = app(GitHubService::class)->GetSettings();

            $organizationProjects = collect(GitHub::api('repo')->org($githubSetting->organization_name, [
                'per_page' => config('github.item_limit'),
                'sort' => 'updated',
                'type' => 'private'
            ]));

            // custom filter added. listed git projects count can be lower than GIT_ITEM_LIMIT.
            // maybe extra organization is useful when filtering projects.

            if (!is_null($githubSetting->topic_name))
            {
                $organizationProjects = $organizationProjects->filter(function ($value) use ($githubSetting) {
                    return in_array($githubSetting->topic_name, $value['topics']);
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

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }
}

<?php

namespace App\Actions\Api\Github;

use GrahamCampbell\GitHub\Facades\GitHub;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

use App\Models\GithubSetting;
use App\Services\GitHubService;

class GetRepositories
{
    use AsAction;

    private GithubSetting $githubSetting;

    // http://developer.github.com/v3/repos/#list-organization-repositories
    public function handle() : JsonResponse
    {
        $response = collect();

        try
        {
            $this->githubSetting = app(GitHubService::class)->GetSettings();

            if ($this->getRepositoryType() !== 'none')
            {
                $organizationProjects = collect(GitHub::api('repo')->org($this->githubSetting->organization_name, [
                    'per_page' => config('github.item_limit'),
                    'sort' => 'updated',
                    'type' => $this->getRepositoryType(),
                ]));

                // custom filter added. listed git projects count can be lower than GIT_ITEM_LIMIT.
                // maybe extra organization is useful when filtering projects.

                if (!is_null($this->githubSetting->topic_name)) {
                    $organizationProjects = $organizationProjects->filter(function ($value) {
                        return in_array($this->githubSetting->topic_name, $value['topics']);
                    });
                }

                $response = $organizationProjects->values()->map(function ($item) {
                    return [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'size' => round($item['size'] / 1024, 2) . 'mb'
                    ];
                });
            }
        }
        catch (\Exception $exception)
        {
            return response()->json([
                'response' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([ 'response' => $response ], Response::HTTP_OK);
    }

    private function getRepositoryType() : string
    {
        $git = $this->githubSetting;

        if ($git->public_repo === true && $git->private_repo === true) { return 'all'; }
        if ($git->public_repo === true) { return 'public'; }
        if ($git->private_repo === true) { return 'private'; }

        return 'none';
    }
}

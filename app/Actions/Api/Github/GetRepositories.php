<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

use App\Services\GitHubService;

class GetRepositories
{
    use AsAction;

    // http://developer.github.com/v3/repos/#list-organization-repositories
    public function handle() : JsonResponse
    {
        $githubService = app(GitHubService::class);

        $searchRepoType = $this->GetRepositoryType();

        // if there is no type of repository specified on workspace settings
        // just return empty bag...
        if ($searchRepoType === 'none')
        {
            return response()->json([ 'response' => collect() ], Response::HTTP_OK);
        }

        // response can include error_code and msg...
        $request = $githubService->MakeGithubRequest('repo', 'org', $githubService->GetOrganizationName(), [
            'per_page' => config('github.item_limit'),
            'sort' => 'updated',
            'type' => $searchRepoType,
        ]);

        $response = collect($request->getData()->response);

        // custom filter added. listed git projects count can be lower than GIT_ITEM_LIMIT.
        // maybe extra organization is useful when filtering projects.
        $filteredOrgProjects = $response->filter(function (\stdClass $project) use ($githubService) {
            return isset($project->topics) && in_array($githubService->GetRepoTopic(), $project->topics);
        });

        // re-organize data layout that returned from api
        $organizationProjects = $filteredOrgProjects->values()->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'size' => round($project->size / 1024, 2) . 'mb'
            ];
        });

        return response()->json([ 'response' => $organizationProjects ], $request->status());
    }

    private function GetRepositoryType() : string
    {
        $githubService = app(GitHubService::class);

        if ($githubService->IsPublicReposEnabled() && $githubService->IsPrivateReposEnabled() === true) { return 'all'; }
        if ($githubService->IsPublicReposEnabled()) { return 'public'; }
        if ($githubService->IsPrivateReposEnabled()) { return 'private'; }

        return 'none';
    }
}

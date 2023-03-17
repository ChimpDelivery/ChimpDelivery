<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

use App\Services\GitHubService;

class GetRepositories
{
    use AsAction;

    // http://developer.github.com/v3/repos/#list-organization-repositories
    public function handle() : JsonResponse
    {
        // if there is no type of repository specified on workspace settings
        // just return empty bag...
        if ($this->GetRepositoryType() === 'none')
        {
            return response()->json([ 'response' => collect() ], Response::HTTP_OK);
        }

        $request = $this->GetGitHubRepositories();

        $orgProjects = collect($request->getData()->response);
        $filteredOrgProjects = $this->FilterProjects($orgProjects);

        return response()->json([
            'response' => $this->ReorganizeProjects($filteredOrgProjects)
        ], $request->status());
    }

    // response can include error_code and msg...
    private function GetGitHubRepositories() : JsonResponse
    {
        $githubService = app(GitHubService::class);

        return $githubService->MakeGithubRequest(
            'repo',
            'org',
            $githubService->GetOrganizationName(),
            [
                'per_page' => config('github.item_limit'),
                'sort' => 'updated',
                'type' => $this->GetRepositoryType(),
            ]
        );
    }

    // custom filter added. listed git projects count can be lower than GIT_ITEM_LIMIT.
    // maybe extra organization is useful when filtering projects.
    private function FilterProjects(Collection $response) : Collection
    {
        $githubService = app(GitHubService::class);

        return $response->filter(function (\stdClass $githubProject) use ($githubService) {
            return isset($githubProject->topics) && in_array($githubService->GetRepoTopic(), $githubProject->topics);
        });
    }

    // re-organize data layout that returned from api
    private function ReorganizeProjects(Collection $filteredOrgProjects) : Collection
    {
        return $filteredOrgProjects->values()->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'size' => round($project->size / 1024, 2) . 'mb'
            ];
        });
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

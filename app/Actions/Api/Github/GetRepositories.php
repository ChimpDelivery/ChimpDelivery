<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

use App\Services\GitHubService;

// api reference: http://developer.github.com/v3/repos/#list-organization-repositories
class GetRepositories
{
    use AsAction;

    public function __construct(
        private readonly GitHubService $githubService
    ) {
    }

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
        return $this->githubService->MakeGithubRequest(
            'repo',
            'org',
            $this->githubService->GetOrganizationName(),
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
        if ($this->githubService->GetRepoTopic() === null)
        {
            return $response;
        }

        return $response->filter(function (\stdClass $githubProject) {
            return isset($githubProject->topics) && in_array($this->githubService->GetRepoTopic(), $githubProject->topics);
        });
    }

    // re-organize data layout that returned from api
    private function ReorganizeProjects(Collection $filteredOrgProjects) : Collection
    {
        return $filteredOrgProjects->values()->map(function (\stdClass $githubProject) {
            return [
                'id' => $githubProject->id,
                'name' => $githubProject->name,
                'size' => round($githubProject->size / 1024, 2) . 'mb'
            ];
        });
    }

    private function GetRepositoryType() : string
    {
        $service = $this->githubService;

        if ($service->IsPublicReposEnabled() && $service->IsPrivateReposEnabled() === true)
        {
            return 'all';
        }
        if ($service->IsPublicReposEnabled())
        {
            return 'public';
        }
        if ($service->IsPrivateReposEnabled())
        {
            return 'private';
        }

        return 'none';
    }
}

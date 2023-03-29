<?php

namespace App\Actions\Api\Github;

use App\Models\AppInfo;
use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Services\GitHubService;

use App\Http\Requests\AppInfo\GetAppInfoRequest;

// api reference: https://docs.github.com/en/rest/branches/branches#list-branches
class GetRepositoryBranches
{
    use AsAction;

    public function __construct(
        private readonly GitHubService $githubService
    ) {
    }

    public function handle(?GetAppInfoRequest $request, ?AppInfo $appInfo = null) : JsonResponse
    {
        $response = $this->githubService->MakeGithubRequest(
            'repo',
            'branches',
            $this->githubService->GetOrganizationName(),
            ($appInfo ?? $request->user()->workspace->apps()->findOrFail($request->validated('id')))->project_name
        );

        $branches = collect($response->getData()->response);

        // api response can include error and details
        // when error is encountered, branch collection will be empty
        $branches = $branches->filter(function (\stdClass $branch) {
            return isset($branch->commit) && isset($branch->name);
        });

        $branches = $branches->values()->map(function (\stdClass $branch) {
            return [
                'name' => $branch->name,
                'commit' => $branch->commit,
            ];
        });

        return response()->json([ 'response' => $branches ], $response->status());
    }
}

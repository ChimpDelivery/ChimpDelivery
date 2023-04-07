<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Services\GitHubService;

class GetUserOrganizations
{
    use AsAction;

    public function __construct(
        private readonly GitHubService $githubService
    ) {
    }

    public function handle() : JsonResponse
    {
        $userOrgs = $this->githubService->MakeGithubRequest('user', 'orgs');
        return response()->json([ 'response' => $userOrgs ]);
    }
}

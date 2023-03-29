<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Services\GitHubService;
use App\Http\Requests\Github\GetRepositoryRequest;

class GetRepository
{
    use AsAction;

    public function __construct(
        private readonly GitHubService $githubService
    ) {
    }

    public function handle(GetRepositoryRequest $request) : JsonResponse
    {
        $response = $this->githubService->MakeGithubRequest(
            'repo',
            'show',
            $this->githubService->GetOrganizationName(),
            $request->validated('project_name')
        );

        return response()->json([ 'response' => $response ], $response->status());
    }
}

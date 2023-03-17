<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

use App\Services\GitHubService;
use App\Http\Requests\Github\GetRepositoryRequest;

class GetRepository
{
    use AsAction;

    public function handle(GetRepositoryRequest $request) : JsonResponse
    {
        $githubService = app(GitHubService::class);

        $response = $githubService->MakeGithubRequest(
            'repo',
            'show',
            $githubService->GetOrganizationName(),
            $request->validated('project_name')
        );

        return response()->json([ 'response' => $response ], $response->status());
    }
}

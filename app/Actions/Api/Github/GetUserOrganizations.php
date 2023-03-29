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
        $request = $this->githubService->MakeGithubRequest('user', 'orgs');
        $response = $request->getData()?->response;

        return response()->json([ 'response' => $response ], $request->status());
    }
}

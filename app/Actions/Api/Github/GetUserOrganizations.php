<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Services\GitHubService;

class GetUserOrganizations
{
    use AsAction;

    public function handle() : JsonResponse
    {
        $response = app(GitHubService::class)->MakeGithubRequest('user', 'orgs');
        return response()->json([ 'response' => $response ], $response->status());
    }
}

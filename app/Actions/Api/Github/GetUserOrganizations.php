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
        $request = app(GitHubService::class)->MakeGithubRequest('user', 'orgs');
        $response = $request->getData()?->response;

        return response()->json([ 'response' => $response ], $request->status());
    }
}

<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Services\GitHubService;

use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetRepositoryBranches
{
    use AsAction;

    public function handle(GetAppInfoRequest $request) : JsonResponse
    {
        $githubService = app(GitHubService::class);

        $response = $githubService->MakeGithubRequest(
            'repo',
            'branches',
            $githubService->GetOrganizationName(),
            Auth::user()->workspace->apps()->findOrFail($request->validated('id'))->project_name
        );

        $branches = collect($response->getData()->response);
        $branches = $branches->values()->map(function ($branch) {
            return [
                'name' => $branch->name,
                'commit' => $branch->commit,
            ];
        });

        return response()->json([ 'response' => $branches ], $response->status());
    }
}

<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Models\AppInfo;
use App\Services\GitHubService;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class GetRepository
{
    use AsAction;

    public function __construct(
        private readonly GitHubService $githubService
    ) {
    }

    public function handle(AppInfo $appInfo) : JsonResponse
    {
        $response = $this->githubService->MakeGithubRequest(
            'repo',
            'show',
            $this->githubService->GetOrganizationName(),
            $appInfo->project_name
        );

        return response()->json([ 'response' => $response['response'] ]);
    }

    public function asController(GetAppInfoRequest $request) : JsonResponse
    {
        return $this->handle(
            $request->user()->workspace->apps()->findOrFail($request->validated('id'))
        );
    }
}

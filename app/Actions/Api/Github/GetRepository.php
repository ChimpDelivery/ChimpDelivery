<?php

namespace App\Actions\Api\Github;

use GrahamCampbell\GitHub\Facades\GitHub;

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
        $response = [];

        try
        {
            $githubSetting = app(GitHubService::class)->setting;

            $response = GitHub::api('repo')->show(
                $githubSetting->organization_name,
                $request->validated('project_name')
            );
        }
        catch (\Exception $exception)
        {
            return response()->json([
                'response' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([ 'response' => $response ], Response::HTTP_OK);
    }
}

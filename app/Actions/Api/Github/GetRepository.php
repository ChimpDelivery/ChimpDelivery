<?php

namespace App\Actions\Api\Github;

use GrahamCampbell\GitHub\Facades\GitHub;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
            $githubSetting = app(GitHubService::class)->GetSettings();

            $response = GitHub::api('repo')->show(
                $githubSetting->organization_name,
                $request->validated('project_name')
            );
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'response' => $exception->getMessage() ], $exception->getCode());
        }

        return response()->json([ 'response' => $response ], Response::HTTP_OK);
    }

    public function authorize(GetRepositoryRequest $request) : bool
    {
        return !Auth::user()->isNew();
    }
}

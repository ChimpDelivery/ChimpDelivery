<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use App\Http\Requests\Github\GetRepositoryRequest;

use GrahamCampbell\GitHub\Facades\GitHub;

class GetRepository extends BaseGithubAction
{
    use AsAction;

    public function handle(GetRepositoryRequest $request) : JsonResponse
    {
        $this->SetGithubSetting($request);
        $this->SetConnectionToken();

        $response = [];

        try
        {
            $response = GitHub::api('repo')->show(
                $this->githubSetting->organization_name,
                $request->validated('project_name')
            );
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'response' => $exception->getMessage() ], $exception->getCode());
        }

        return response()->json([ 'response' => $response ], Response::HTTP_OK);
    }
}

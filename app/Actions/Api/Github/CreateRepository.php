<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Github\GetRepositoryRequest;

use GrahamCampbell\GitHub\Facades\GitHub;

class CreateRepository extends BaseGithubAction
{
    use AsAction;

    // https://docs.github.com/en/rest/repos/repos#create-a-repository-using-a-template
    public function handle(GetRepositoryRequest $request) : JsonResponse
    {
        $this->SetGithubSetting($request);
        $this->SetConnectionToken();

        $response = [];

        try
        {
            $response = GitHub::api('repo')->createFromTemplate(
                $this->githubSetting->organization_name,
                $this->githubSetting->template_name,
                [
                    'name' => $request->validated('project_name'),
                    'description' => '',
                    'owner' => $this->githubSetting->organization_name,
                    'include_all_branches' => false,
                    'private' => true
                ]
            );

            $this->UpdateRepoTopics($request);
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'response' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json([ 'response' => $response ], Response::HTTP_OK);
    }

    public function UpdateRepoTopics(GetRepositoryRequest $request) : JsonResponse
    {
        $response = [];

        try
        {
            $gitSetting = Auth::user()->workspace->githubSetting;

            $response = GitHub::api('repo')->replaceTopics(
                $gitSetting->organization_name,
                $request->validated('project_name'),
                [ $gitSetting->topic_name ]
            );
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'response' => $exception->getMessage() ], $exception->getCode());
        }

        return response()->json([ 'response' => $response ], Response::HTTP_OK);
    }
}

<?php

namespace App\Actions\Api\Github;

use GrahamCampbell\GitHub\Facades\GitHub;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use App\Services\GitHubService;
use App\Http\Requests\Github\GetRepositoryRequest;

class CreateRepository
{
    use AsAction;

    // https://docs.github.com/en/rest/repos/repos#create-a-repository-using-a-template
    public function handle(GetRepositoryRequest $request) : JsonResponse
    {
        $response = [];

        try
        {
            $githubSetting = app(GitHubService::class)->GetSettings();

            $response = GitHub::api('repo')->createFromTemplate(
                $githubSetting->organization_name,
                $githubSetting->template_name,
                [
                    'name' => $request->validated('project_name'),
                    'description' => '',
                    'owner' => $githubSetting->organization_name,
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
            $githubSetting = app(GitHubService::class)->GetSettings();

            $response = GitHub::api('repo')->replaceTopics(
                $githubSetting->organization_name,
                $request->validated('project_name'),
                [ $githubSetting->topic_name ]
            );
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'response' => $exception->getMessage() ], $exception->getCode());
        }

        return response()->json([ 'response' => $response ], Response::HTTP_OK);
    }

    /* Responses
    $flashMessage = match($createAppResponse->git->status)
    {
        Response::HTTP_OK => "Project: <b>{$projectName}</b> created as new Git Project.", // new git project
        Response::HTTP_UNPROCESSABLE_ENTITY => "Project: <b>{$projectName}</b> created.", // git project already exist
        Response::HTTP_NOT_FOUND => "Error: Git project couldn't created! Make sure there is an valid template project on Github Organization.",
        default => "Git Status: {$createAppResponse->git->status}",
    };
    */
}

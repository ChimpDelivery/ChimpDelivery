<?php

namespace App\Http\Controllers;

use App\Http\Requests\Github\GetRepositoryRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;

use GrahamCampbell\GitHub\Facades\GitHub;

class GithubController extends Controller
{
    // http://developer.github.com/v3/repos/#list-organization-repositories
    public function GetRepositories() : JsonResponse
    {
        $response = [];

        try
        {
            $gitWorkspace = Auth::user()->workspace;

            $organizationProjects = collect(GitHub::api('repo')->org($gitWorkspace->github_org_name, [
                'per_page' => config('github.item_limit'),
                'sort' => 'updated',
                'type' => 'private'
            ]));

            // custom filter added. listed git projects count can be lower than GIT_ITEM_LIMIT.
            // maybe extra organization is useful when filtering projects.
            $filteredOrganizationProjects = $organizationProjects->filter(function ($value) use ($gitWorkspace) {
                return in_array($gitWorkspace->github_topic, $value['topics']);
            });

            $response = $filteredOrganizationProjects->values()->map(function ($item) {
                return [
                    'id'   => $item['id'],
                    'name' => $item['name'],
                    'size' => round($item['size'] / 1024, 2) . 'mb'
                ];
            });
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'status' => $exception->getCode() ]);
        }

        return response()->json([ 'status' => Response::HTTP_OK, 'response' => $response ]);
    }

    public function GetRepository(GetRepositoryRequest $request) : JsonResponse
    {
        $response = [];

        try
        {
            $gitWorkspace = Auth::user()->workspace;

            $response = GitHub::api('repo')->show($gitWorkspace->github_org_name, $request->validated('project_name'));
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'status' => $exception->getCode(), 'response' => $exception->getMessage() ]);
        }

        return response()->json([ 'status' => Response::HTTP_OK, 'response' => $response ]);
    }

    // https://docs.github.com/en/rest/repos/repos#create-a-repository-using-a-template
    public function CreateRepository(GetRepositoryRequest $request) : JsonResponse
    {
        $response = [];

        try
        {
            $gitWorkspace = Auth::user()->workspace;

            $response = GitHub::api('repo')->createFromTemplate(
                $gitWorkspace->github_org_name,
                $gitWorkspace->github_template,
                [
                    'name' => $request->validated('project_name'),
                    'description' => '',
                    'owner' => $gitWorkspace->github_org_name,
                    'include_all_branches' => false,
                    'private' => true
                ]
            );

            $this->UpdateRepoTopics($request);
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'status' => $exception->getCode(), 'response' => $exception->getMessage() ]);
        }

        return response()->json([ 'status' => Response::HTTP_OK, 'response' => $response ]);
    }

    public function UpdateRepoTopics(GetRepositoryRequest $request)
    {
        $response = [];

        try
        {
            $gitWorkspace = Auth::user()->workspace;

            $response = GitHub::api('repo')->replaceTopics(
                $gitWorkspace->github_org_name,
                $request->validated('project_name'),
                [
                    $gitWorkspace->github_topic,
                ]
            );
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'status' => $exception->getCode(), 'response' => $exception->getMessage() ]);
        }

        return response()->json([ 'status' => Response::HTTP_OK, 'response' => $response ]);
    }
}

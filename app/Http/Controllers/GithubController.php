<?php

namespace App\Http\Controllers;

use App\Http\Requests\Github\GetRepositoryRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;

use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Support\Facades\Config;

class GithubController extends Controller
{
    public function __construct()
    {
        // is it safe?
        Config::set(
            'github.connections.main.token',
            Auth::user()->workspace->githubSetting->personal_access_token ?? 'INVALID_TOKEN'
        );
    }

    // http://developer.github.com/v3/repos/#list-organization-repositories
    public function GetRepositories() : JsonResponse
    {
        $response = collect();

        try
        {
            $gitSetting = Auth::user()->workspace->githubSetting;

            $organizationProjects = collect(GitHub::api('repo')->org($gitSetting->organization_name, [
                'per_page' => config('github.item_limit'),
                'sort' => 'updated',
                'type' => 'private'
            ]));

            // custom filter added. listed git projects count can be lower than GIT_ITEM_LIMIT.
            // maybe extra organization is useful when filtering projects.

            if (!is_null($gitSetting->topic_name))
            {
                $organizationProjects = $organizationProjects->filter(function ($value) use ($gitSetting) {
                    return in_array($gitSetting->topic_name, $value['topics']);
                });
            }

            $response = $organizationProjects->values()->map(function ($item) {
                return [
                    'id'   => $item['id'],
                    'name' => $item['name'],
                    'size' => round($item['size'] / 1024, 2) . 'mb'
                ];
            });
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'status' => $exception->getCode(), 'response' => $response ]);
        }

        return response()->json([ 'status' => Response::HTTP_OK, 'response' => $response ]);
    }

    public function GetRepository(GetRepositoryRequest $request) : JsonResponse
    {
        $response = [];

        try
        {
            $gitSetting = Auth::user()->workspace->githubSetting;

            $response = GitHub::api('repo')->show($gitSetting->organization_name, $request->validated('project_name'));
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
            $gitSetting = Auth::user()->workspace->githubSetting;

            $response = GitHub::api('repo')->createFromTemplate(
                $gitSetting->organization_name,
                $gitSetting->template_name,
                [
                    'name' => $request->validated('project_name'),
                    'description' => '',
                    'owner' => $gitSetting->organization_name,
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
            $gitSetting = Auth::user()->workspace->githubSetting;

            $response = GitHub::api('repo')->replaceTopics(
                $gitSetting->organization_name,
                $request->validated('project_name'),
                [
                    $gitSetting->topic_name,
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

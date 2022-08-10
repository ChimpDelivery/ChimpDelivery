<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInfo\StoreAppInfoRequest;
use App\Http\Requests\Github\GetRepositoryRequest;

use Illuminate\Http\JsonResponse;

use GrahamCampbell\GitHub\Facades\GitHub;

class GithubController extends Controller
{
    // http://developer.github.com/v3/repos/#list-organization-repositories
    public function GetRepositories() : JsonResponse
    {
        $response = [];

        try
        {
            $organizationProjects = collect(GitHub::api('repo')->org(config('github.organization_name'), [
                'per_page' => config('github.item_limit'),
                'sort' => 'updated',
                'type' => 'private'
            ]));

            // custom filter added. listed git projects count can be lower than GIT_ITEM_LIMIT.
            // maybe extra organization is useful when filtering projects.
            $filteredOrganizationProjects = $organizationProjects->filter(function ($value) {
                return in_array(config('github.prototype_topic'), $value['topics']);
            });

            $response = $filteredOrganizationProjects->values()->map(function ($item) {
                return [
                    'id'   => $item['id'],
                    'name' => $item['name'],
                    'size' => round($item['size'] / 1024, 2) . 'mb'
                ];
            });

            return response()->json($response);
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'status' => $exception->getCode() ]);
        }

        return response()->json($response);
    }

    public function GetRepository(GetRepositoryRequest $request) : JsonResponse
    {
        $response = [];

        try
        {
            $response = GitHub::api('repo')->show(config('github.organization_name'), $request->validated('project_name'));
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'status' => $exception->getCode() ]);
        }

        return response()->json($response);
    }

    // https://docs.github.com/en/rest/repos/repos#create-a-repository-using-a-template
    public function CreateRepository(GetRepositoryRequest $request) : JsonResponse
    {
        $response = [];

        try
        {
            $response = GitHub::api('repo')->createFromTemplate(
                config('github.organization_name'),
                config('github.template_project'),
                [
                    'name' => $request->validated('project_name'),
                    'description' => '',
                    'owner' => config('github.organization_name'),
                    'include_all_branches' => false,
                    'private' => true
                ]
            );

            $this->UpdateRepoTopics($request->validated('project_name'));
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'status' => $exception->getCode() ]);
        }

        return response()->json($response);
    }

    public function UpdateRepoTopics(string $repositoryName)
    {
        $response = [];

        try
        {
            $response = GitHub::api('repo')->replaceTopics(
                config('github.organization_name'),
                $repositoryName,
                [ config('github.prototype_topic') ]
            );
        }
        catch (\Exception $exception)
        {
            return response()->json([ 'message' => $exception->getMessage() ]);
        }

        return response()->json($response);
    }
}

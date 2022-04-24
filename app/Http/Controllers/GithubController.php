<?php

namespace App\Http\Controllers;

use Github\Exception\RuntimeException;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class GithubController extends Controller
{
    // http://developer.github.com/v3/repos/#list-organization-repositories
    public function GetRepositories() : JsonResponse
    {
        $response = [];

        try
        {
            $organizationProjects = collect(GitHub::api('repo')->org('TalusStudio', [
                'per_page' => config('github.item_limit'),
                'sort' => 'updated',
                'type' => 'private'
            ]));

            // custom filter added. listed git projects count can be lower than GIT_ITEM_LIMIT.
            // maybe extra organization is useful when filtering projects.
            $filteredOrganizationProjects = $organizationProjects->filter(function ($value) {
                return !Str::contains($value['name'], 'deprecated', true) &&
                    !Str::contains($value['name'], 'backend', true) &&
                    !Str::contains($value['name'], 'package', true) &&
                    !Str::contains($value['name'], '.github', true);
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
        catch (RuntimeException $exception)
        {
            return response()->json(['message' => $exception->getMessage()]);
        }
        finally
        {
            return response()->json($response);
        }
    }

    public function GetRepository(Request $request) : JsonResponse
    {
        $response = [];

        try
        {
            $project = GitHub::api('repo')->showById($request->id);

            $response = [
                'id' => $project['name'],
                'name' => $project['name'],
                'size' => $project['size'],
                'url' => $project['html_url']
            ];
        }
        catch (RuntimeException $exception)
        {
            return response()->json(['message' => $exception->getMessage()]);
        }
        finally
        {
            return response()->json($response);
        }
    }
}

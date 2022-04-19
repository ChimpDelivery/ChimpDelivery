<?php

namespace App\Http\Controllers;

use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\JsonResponse;

class GithubController extends Controller
{
    public function GetRepositories() : JsonResponse
    {
        $response = collect();

        // http://developer.github.com/v3/repos/#list-organization-repositories
        $organizationProjects = collect(GitHub::api('repo')->org('TalusStudio', [
            'per_page' => 30,
            'sort' => 'created',
            'type' => 'private'
        ]));

        $projectInfos = $organizationProjects->pluck('id', 'name');

        $projectInfos->each(function ($item, $key) use ($response, $projectInfos) {
            $response->add(['id' => $item, 'name' => $key]);
        });

        return response()->json($response);
    }
}

<?php

namespace App\Http\Controllers;

use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\JsonResponse;

class GithubController extends Controller
{
    public function GetRepositories() : JsonResponse
    {
        // http://developer.github.com/v3/repos/#list-organization-repositories
        $organizationProjects = collect(GitHub::api('repo')->org('TalusStudio', [
            'per_page' => 30,
            'sort' => 'updated',
            'type' => 'private'
        ]));

        $response = $organizationProjects->map(function ($item) {
            return ['id' => $item['id'], 'name' => $item['name'], 'size' => round($item['size'] / 1024, 2) . 'mb'];
        });

        return response()->json($response);
    }
}

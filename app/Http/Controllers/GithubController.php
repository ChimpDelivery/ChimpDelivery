<?php

namespace App\Http\Controllers;

use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GithubController extends Controller
{
    public function GetRepositories() : JsonResponse
    {
        $response = collect();

        // organization - type - page
        $organizationProjects = collect(GitHub::api('organization')->repositories('TalusStudio', 'private', 1));
        $projectInfos = $organizationProjects->pluck('id', 'name');

        $projectInfos->each(function ($item, $key) use ($response, $projectInfos) {
            $response->add(['id' => $item, 'name' => $key]);
        });

        return response()->json($response);
    }
}

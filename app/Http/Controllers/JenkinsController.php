<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JenkinsController extends Controller
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('jenkins.host').'/job/'.config('jenkins.ws');
    }

    public function GetJobList(Request $request) : JsonResponse
    {
        $url = $this->baseUrl.'/api/json';
        $jenkinsInfo = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(5)
            ->connectTimeout(2)
            ->get($url);
        $jenkinsJobList = collect(json_decode($jenkinsInfo)->jobs);

        return response()->json([
            'job_list' => $jenkinsJobList->pluck('name')
        ]);
    }

    public function GetJob(Request $request) : JsonResponse
    {
        $url = $this->baseUrl."/job/{$request->projectName}/api/json";
        $jenkinsInfo = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(5)
            ->connectTimeout(2)
            ->get($url);
        $jenkinsJobInfo = collect(json_decode($jenkinsInfo));

        return response()->json([
            'job' => $jenkinsJobInfo
        ]);
    }

    // todo: refactor
    public function GetBuildList(Request $request, $appName = null) : JsonResponse
    {
        $app = is_null($appName) ? $request->projectName : $appName;

        $url = $this->baseUrl."/job/{$app}/job/master/api/json";
        $jenkinsInfo = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(5)
            ->connectTimeout(2)
            ->get($url);
        $retrievedData = json_decode($jenkinsInfo);

        if (!$retrievedData)
        {
            return response()->json([
                'build_list' => null
            ]);
        }

        if (isset($retrievedData->builds) && !empty($retrievedData->builds))
        {
            $jenkinsJobBuildList = collect(json_decode($jenkinsInfo)->builds);

            return response()->json([
                'build_list' => $jenkinsJobBuildList
            ]);
        }

        // todo: bug fix when repo removed in jenkins
        // probably $retrievedData when null
        if (isset($retrievedData->nextBuildNumber) && $retrievedData->nextBuildNumber == 1)
        {
            return response()->json([
                'build_list' => 'FIRST_BUILD'
            ]);
        }

        return response()->json([
            'build_list' => []
        ]);
    }

    public function GetLatestBuildNumber(Request $request, $appName = null) : JsonResponse
    {
        $app = is_null($appName) ? $request->projectName : $appName;

        $retrievedData = $this->GetBuildList($request, $app)->getData();

        // todo: refactor error codes.
        // -3 => workspace exists, but there is no builds.
        // -2 => workspace exists, first build triggered, but all builds cleared.
        // -1 => workspace doesn't exists.

        if (is_null($retrievedData->build_list))
        {
            return response()->json([
                'latest_build_number' => -1,
                'jenkins_url' => ''
            ]);
        }

        if ($retrievedData->build_list == 'FIRST_BUILD')
        {
            return response()->json([
                'latest_build_number' => -3,
                'jenkins_url' => ''
            ]);
        }

        return response()->json([
            'latest_build_number' => !empty($retrievedData->build_list) ? $retrievedData->build_list[0]->number : -2,
            'jenkins_url' => !empty($retrievedData->build_list) ? $retrievedData->build_list[0]->url : ''
        ]);
    }

    // todo: replace with master/lastBuild/
    public function GetLatestBuildInfo(Request $request, $appName = null, $buildNumber = null) : JsonResponse
    {
        if (!config('jenkins.enabled')) {
            return response()->json([
                'latest_build_status' => 'JENKINS DOWN!'
            ]);
        }

        $app = is_null($appName) ? $request->projectName : $appName;
        $appBuildNumber = is_null($buildNumber) ? $request->buildNumber : $buildNumber;

        $isProjectValid = $this->GetLatestBuildNumber($request, $app)->getData();
        if ($isProjectValid->latest_build_number == -1)
        {
            return response()->json([
                'latest_build_status' => 'MISSING'
            ]);
        }

        $url = $this->baseUrl."/job/{$app}/job/master/{$appBuildNumber}/api/json";
        $jenkinsInfo = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(5)
            ->connectTimeout(2)
            ->get($url);
        $retrievedData = json_decode($jenkinsInfo);

        $isBuilding = isset($retrievedData->building) && $retrievedData->building == true;

        $response = $isBuilding ? 'BUILDING' : (isset($retrievedData->result) ? $retrievedData->result : '');

        if ($response == 'BUILDING')
        {
            return response()->json([
                'latest_build_status' => 'BUILDING',
                'estimated_duration' => $retrievedData->estimatedDuration,
                'timestamp' => $retrievedData->timestamp
            ]);
        }

        return response()->json([
            'latest_build_status' => $response
        ]);
    }

    public function PostStopJob(Request $request, $appName = null, $buildNumber = null) : void
    {
        $app = is_null($appName) ? $request->projectName : $appName;
        $appBuildNumber = is_null($buildNumber) ? $request->buildNumber : $buildNumber;

        $url = $this->baseUrl."/job/{$app}/job/master/{$appBuildNumber}/stop";

        Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url);
    }
}

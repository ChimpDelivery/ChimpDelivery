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
        $jenkinsInfo = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->get($url);
        $jenkinsJobList = collect(json_decode($jenkinsInfo)->jobs);

        return response()->json([
            'job_list' => $jenkinsJobList->pluck('name')
        ]);
    }

    public function GetJob(Request $request) : JsonResponse
    {
        $url = $this->baseUrl."/job/{$request->projectName}/api/json";
        $jenkinsInfo = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->get($url);
        $jenkinsJobInfo = collect(json_decode($jenkinsInfo));

        return response()->json([
            'job' => $jenkinsJobInfo
        ]);
    }

    public function GetBuildList(Request $request, $appName = null) : JsonResponse
    {
        $app = is_null($appName) ? $request->projectName : $appName;

        $url = $this->baseUrl."/job/{$app}/job/master/api/json";
        $jenkinsInfo = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->get($url);
        $retrievedData = json_decode($jenkinsInfo);

        if (isset($retrievedData->builds)) {
            $jenkinsJobBuildList = collect(json_decode($jenkinsInfo)->builds);

            return response()->json([
                'build_list' => $jenkinsJobBuildList
            ]);
        }

        return response()->json([
            'build_list' => null
        ]);
    }

    public function GetLatestBuildNumber(Request $request, $appName = null) : JsonResponse
    {
        $app = is_null($appName) ? $request->projectName : $appName;

        $retrievedData = $this->GetBuildList($request, $app)->getData();

        // refactor error codes.
        // -2 => workspace exists but there is no builds.
        // -1 => workspace doesn't exists.
        return response()->json([
            'latest_build_number' => !is_null($retrievedData->build_list) ?
                (!empty($retrievedData->build_list) ? $retrievedData->build_list[0]->number : -2) : -1,
            'jenkins_url' => !empty($retrievedData->build_list) ? $retrievedData->build_list[0]->url : -1
        ]);
    }

    public function GetLatestBuildInfo(Request $request, $appName = null, $buildNumber = null) : JsonResponse
    {
        if (config('jenkins.enabled') == false) {
            return response()->json([
                'latest_build_status' => 'JENKINS DOWN!'
            ]);
        }

        $app = is_null($appName) ? $request->projectName : $appName;
        $appBuildNumber = is_null($buildNumber) ? $request->buildNumber : $buildNumber;

        $url = $this->baseUrl."/job/{$app}/job/master/{$appBuildNumber}/api/json";
        $jenkinsInfo = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->get($url);
        $retrievedData = json_decode($jenkinsInfo);

        $isBuilding = isset($retrievedData->building) && $retrievedData->building == true;

        return response()->json([
            'latest_build_status' => $isBuilding ? 'BUILDING' : (isset($retrievedData->result) ? $retrievedData->result : -1)
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

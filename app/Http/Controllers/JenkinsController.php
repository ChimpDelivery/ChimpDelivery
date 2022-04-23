<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class JenkinsController extends Controller
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('jenkins.host').'/job/'.config('jenkins.ws');
    }

    public function GetJob(Request $request, $appName = null) : JsonResponse
    {
        $app = is_null($appName) ? $request->projectName : $appName;

        $url = $this->baseUrl."/job/{$app}/api/json";
        $jenkinsInfo = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(5)
            ->connectTimeout(2)
            ->get($url);
        $jenkinsJobInfo = collect(json_decode($jenkinsInfo));

        return response()->json([
            'job' => $jenkinsJobInfo
        ]);
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

    public function GetBuildList(Request $request, $appName = null) : JsonResponse
    {
        $app = is_null($appName) ? $request->projectName : $appName;

        $url = $this->baseUrl."/job/{$app}/job/master/api/json";
        $jenkinsInfo = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(5)
            ->connectTimeout(2)
            ->get($url);
        $retrievedData = json_decode($jenkinsInfo);

        // job doesn't exists.
        if (!$retrievedData) {
            return response()->json([
                'build_list' => null
            ]);
        }

        // job exists but there is no build at all.
        if (isset($retrievedData->nextBuildNumber) && $retrievedData->nextBuildNumber == 1) {
            return response()->json([
                'build_list' => []
            ]);
        }

        // job exists, builds exists.
        $jenkinsJobBuildList = collect();

        if (isset($retrievedData->builds) && !empty($retrievedData->builds)) {
            $jenkinsJobBuildList = $jenkinsJobBuildList->merge($retrievedData->builds);
        }

        // job exists, but builds are deleted. add nextBuildNumber value to build list for detailed info.
        if (isset($retrievedData->builds) && empty($retrievedData->builds)) {

            $additionalBuildInfo = collect([
                '_class' => '',
                'number' => $retrievedData->nextBuildNumber,
                'url' => ''
            ]);

            $jenkinsJobBuildList = $jenkinsJobBuildList->add($additionalBuildInfo);
        }

        return response()->json([
            'build_list' => $jenkinsJobBuildList
        ]);
    }

    public function GetLatestBuildInfo(Request $request, $appName = null) : JsonResponse
    {
        $app = is_null($appName) ? $request->projectName : $appName;

        $url = $this->baseUrl."/job/{$app}/job/master/lastBuild/api/json";
        $jenkinsInfo = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(5)
            ->connectTimeout(2)
            ->get($url);
        $retrievedData = json_decode($jenkinsInfo);

        $jobExists = !empty($this->GetJob($request, $app)->getData()->job);
        if ($jobExists)
        {
            $jobIsBuilding = $retrievedData && isset($retrievedData->building) && $retrievedData->building == true;
            $jobStatus = ($jobIsBuilding) ? 'BUILDING' : (($retrievedData) ? $retrievedData->result : '');

            $lastBuildNumberData = $this->GetBuildList($request, $app)->getData();
            $buildNumber = (isset($lastBuildNumberData->build_list[0]) ? $lastBuildNumberData->build_list[0]->number : '');

            $changeSets = $retrievedData && isset($retrievedData->changeSets[0]) ? collect($retrievedData->changeSets[0]->items)->pluck('comment') : [];

            $response = collect([
                'job_exists' => $jobExists,
                'build_status' => $jobStatus,
                'build_number' => $buildNumber,
                'change_sets' => $changeSets,
                'estimated_duration' => $retrievedData ? $retrievedData->estimatedDuration : '',
                'timestamp' => $retrievedData ? $retrievedData->timestamp : '',
                'jenkins_url' => $retrievedData ? Str::replace('http://localhost:8080', config('jenkins.host'), $retrievedData->url) : ''
            ]);

            return response()->json($response);
        }
        else
        {
            return response()->json([
                'job_exists' => false
            ]);
        }
    }

    public function PostStopJob(Request $request, $appName = null, $buildNumber = null) : void
    {
        $app = is_null($appName) ? $request->projectName : $appName;
        $appBuildNumber = is_null($buildNumber) ? $request->buildNumber : $buildNumber;

        $url = $this->baseUrl."/job/{$app}/job/master/{$appBuildNumber}/stop";

        Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url);
    }
}

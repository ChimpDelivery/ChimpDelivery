<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JenkinsController extends Controller
{
    public function GetJobList(Request $request) : JsonResponse
    {
        $url = implode('', [
            env('JENKINS_HOST', 'http://localhost:8080'),
            "/job/Talus-WorkSpace/api/json"
        ]);

        $jenkinsInfo = Http::withBasicAuth(env('JENKINS_USER'), env('JENKINS_TOKEN'))->get($url);
        $jenkinsJobList = collect(json_decode($jenkinsInfo)->jobs);

        return response()->json([
            'job_list' => $jenkinsJobList->pluck('name')
        ]);
    }

    public function GetJob(Request $request) : JsonResponse
    {
        $url = implode('', [
            env('JENKINS_HOST', 'http://localhost:8080'),
            "/job/Talus-WorkSpace/job/{$request->projectName}/api/json"
        ]);

        $jenkinsInfo = Http::withBasicAuth(env('JENKINS_USER'), env('JENKINS_TOKEN'))->get($url);
        $jenkinsJobInfo = collect(json_decode($jenkinsInfo));

        return response()->json([
            'job' => $jenkinsJobInfo
        ]);
    }

    public function GetBuildList(Request $request, $appName = null) : JsonResponse
    {
        if (env('JENKINS_ENABLED') == false) {            
            return response()->json(['build_list' => '']);
        }

        $app = is_null($appName) ? $request->projectName : $appName;

        $url = implode('', [
            env('JENKINS_HOST', 'http://localhost:8080'),
            "/job/Talus-WorkSpace/job/{$app}/job/master/api/json"
        ]);

        $jenkinsInfo = Http::withBasicAuth(env('JENKINS_USER'), env('JENKINS_TOKEN'))->get($url);
        
        $retrievedData = json_decode($jenkinsInfo);
        if (isset($retrievedData->builds))
        {
            $jenkinsJobBuildList = collect(json_decode($jenkinsInfo)->builds);

            return response()->json([
                'build_list' => $jenkinsJobBuildList
            ]);
        }

        return response()->json([
            'build_list' => ''
        ]);
    }

    public function GetLatestBuildNumber(Request $request, $appName = null) : JsonResponse
    {
        $retrievedData = $this->GetBuildList($request, $appName)->getData();
        
        return response()->json([
            'latest_build_number' => !empty($retrievedData->build_list) ? $retrievedData->build_list[0]->number : -1,
            'jenkins_url' => !empty($retrievedData->build_list) ? $retrievedData->build_list[0]->url : -1
        ]);
    }

    public function GetLatestBuildInfo(Request $request, $appName = null, $buildNumber = null) : JsonResponse
    {
        if (env('JENKINS_ENABLED') == false) {
            return response()->json(['latest_build_status' => 'JENKINS DOWN!']);
        }

        $app = is_null($appName) ? $request->projectName : $appName;
        $appBuildNumber = is_null($buildNumber) ? $request->buildNumber : $buildNumber; 

        $url = implode('', [
            env('JENKINS_HOST', 'http://localhost:8080'),
            "/job/Talus-WorkSpace/job/{$app}/job/master/{$appBuildNumber}/api/json"
        ]);

        $jenkinsInfo = Http::withBasicAuth(env('JENKINS_USER'), env('JENKINS_TOKEN'))->get($url);
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

        $url = implode('', [
            env('JENKINS_HOST', 'http://localhost:8080'),
            "/job/Talus-WorkSpace/job/{$app}/job/master/{$appBuildNumber}/stop"
        ]);

        Http::withBasicAuth(env('JENKINS_USER'), env('JENKINS_TOKEN'))->post($url);
    }
}

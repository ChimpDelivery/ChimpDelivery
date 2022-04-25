<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class JenkinsController extends Controller
{
    private string $baseUrl;

    private function GetJenkinsApi($url)
    {
        return Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(5)
            ->connectTimeout(2)
            ->get($url);
    }

    public function __construct()
    {
        $this->baseUrl = config('jenkins.host').'/job/'.config('jenkins.ws');
    }

    public function GetJob(Request $request, $job = null) : JsonResponse
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $jenkinsInfo = $this->GetJenkinsApi($this->baseUrl."/job/{$jobName}/api/json");
        $response = collect(json_decode($jenkinsInfo));

        return response()->json([
            'job' => $response
        ]);
    }

    public function GetJobList() : JsonResponse
    {
        $jenkinsInfo = $this->GetJenkinsApi($this->baseUrl.'/api/json');
        $response = collect(json_decode($jenkinsInfo)->jobs);

        return response()->json([
            'job_list' => $response->pluck('name')
        ]);
    }

    public function GetBuildList(Request $request, $job = null) : JsonResponse
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $jenkinsInfo = $this->GetJenkinsApi($this->baseUrl."/job/{$jobName}/job/master/api/json");
        $retrievedData = json_decode($jenkinsInfo);

        // job doesn't exists.
        if (!$retrievedData)
        {
            return response()->json([
                'build_list' => null
            ]);
        }

        $response = collect();
        $response = $response->merge($retrievedData?->builds);

        // job exists, but builds are deleted. add nextBuildNumber value to build list for detailed info.
        if (isset($retrievedData->builds) && empty($retrievedData->builds))
        {
            $additionalBuildInfo = collect([
                '_class' => '',
                'number' => $retrievedData->nextBuildNumber,
                'url' => ''
            ]);

            $response = $response->add($additionalBuildInfo);
        }

        return response()->json([
            'build_list' => $response
        ]);
    }

    public function GetLatestBuildInfo(Request $request, $job = null) : JsonResponse
    {
        $jobName = is_null($job) ? $request->projectName : $job;

        try
        {
            $jenkinsInfo = $this->GetJenkinsApi($this->baseUrl . "/job/{$jobName}/job/master/lastBuild/api/json");
        }
        catch (ConnectionException|RequestException $exception)
        {
            $response = collect([
                'jenkins_status' => false,
                'jenkins_message' => $exception->getMessage(),
                'job_exists' => false
            ]);

            return response()->json($response);
        }

        $response = collect(['job_exists' => false]);

        $jobExists = !empty($this->GetJob($request, $jobName)->getData()->job);
        if ($jobExists)
        {
            $retrievedData = json_decode($jenkinsInfo);

            $jobIsBuilding = $retrievedData?->building == true;
            $jobStatus = ($jobIsBuilding) ? 'BUILDING' : (!$retrievedData ? 'NO_BUILD' : $retrievedData->result);

            $lastBuildNumberData = $this->GetBuildList($request, $jobName)->getData();
            $buildNumber = (isset($lastBuildNumberData->build_list[0]) ? $lastBuildNumberData->build_list[0]->number : '');
            $changeSets = isset($retrievedData->changeSets[0]) ? collect($retrievedData->changeSets[0]->items)->pluck('comment') : [];

            $response->put('job_exists', true);
            $response->put('build_status', $jobStatus);
            $response->put('build_number', $buildNumber);
            $response->put('change_sets', $changeSets);
            $response->put('estimated_duration', $retrievedData?->estimatedDuration);
            $response->put('timestamp', $retrievedData?->timestamp);
            $response->put('jenkins_url', Str::replace('http://localhost:8080', config('jenkins.host'), $retrievedData?->url));
        }

        return response()->json($response);
    }

    public function PostStopJob(Request $request, $job = null, $buildNumber = null) : void
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $appBuildNumber = is_null($buildNumber) ? $request->buildNumber : $buildNumber;

        $url = $this->baseUrl."/job/{$jobName}/job/master/{$appBuildNumber}/stop";

        Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url);
    }
}

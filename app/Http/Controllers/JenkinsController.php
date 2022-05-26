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

    public function GetJob(Request $request, $job = null) : JsonResponse
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $jenkinsInfo = self::GetJenkinsApi($this->baseUrl."/job/{$jobName}/api/json");

        return response()->json([
            'job' => collect($jenkinsInfo)->only(['url', 'name'])
        ]);
    }

    public function GetJobList() : JsonResponse
    {
        $jenkinsInfo = self::GetJenkinsApi($this->baseUrl.'/api/json');

        return response()->json([
            'job_list' => collect($jenkinsInfo->jobs)->pluck('name')
        ]);
    }

    public function GetBuildList(Request $request, $job = null) : JsonResponse
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $jenkinsInfo = self::GetJenkinsApi($this->baseUrl."/job/{$jobName}/job/master/api/json");

        // job doesn't exists.
        if (!$jenkinsInfo)
        {
            return response()->json([
                'build_list' => null
            ]);
        }

        $response = collect();
        $response = $response->merge($jenkinsInfo?->builds);

        // job exists, but builds are deleted. add nextBuildNumber value to build list for detailed info.
        if (isset($jenkinsInfo->builds) && empty($jenkinsInfo->builds))
        {
            $additionalBuildInfo = collect([
                '_class' => '',
                'number' => $jenkinsInfo->nextBuildNumber,
                'url' => ''
            ]);

            $response = $response->add($additionalBuildInfo);
        }

        return response()->json([
            'build_list' => $response
        ]);
    }

    // todo: refactor
    public function GetLatestBuildInfo(Request $request, $job = null) : JsonResponse
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $jenkinsInfo = [];

        try
        {
            $jenkinsInfo = self::GetJenkinsApi($this->baseUrl . "/job/{$jobName}/job/master/lastBuild/api/json");
        }
        catch (\Exception $exception)
        {
            $response = collect([
                'jenkins_status' => false,
                'jenkins_message' => $exception->getMessage(),
                'job_exists' => false
            ]);

            return response()->json($response);
        }
        finally
        {
            $response = collect(['job_exists' => false]);

            $jobExists = !empty($this->GetJob($request, $jobName)->getData()->job);
            if ($jobExists)
            {
                $jobIsBuilding = $jenkinsInfo?->building == true;
                $jobStatus = ($jobIsBuilding) ? 'BUILDING' : (!$jenkinsInfo ? 'NO_BUILD' : $jenkinsInfo->result);

                $changeSets = isset($jenkinsInfo->changeSets[0])
                    ? collect($jenkinsInfo->changeSets[0]->items)->pluck('msg')
                    : collect();

                $jobStageInfo = self::GetJenkinsApi($this->baseUrl . "/job/{$jobName}/job/master/{$jenkinsInfo?->id}/wfapi/describe");
                $stages = collect($jobStageInfo?->stages ?? []);

                $message = $stages->firstWhere('status', 'FAILED')?->name;

                $response->put('job_exists', true);
                $response->put('build_number', $jenkinsInfo?->id);
                $response->put('build_status', collect(['status' => $jobStatus, 'message' => $message]));
                $response->put('build_stage', $stages->last()?->name);
                $response->put('change_sets', $changeSets);
                $response->put('estimated_duration', $jenkinsInfo?->estimatedDuration);
                $response->put('timestamp', $jenkinsInfo?->timestamp);
                $response->put('jenkins_url', $jenkinsInfo?->url);
            }

            return response()->json($response);
        }
    }

    public function PostStopJob(Request $request, $job = null, $buildNumber = null) : void
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $appBuildNumber = is_null($buildNumber) ? $request->buildNumber : $buildNumber;

        $url = $this->baseUrl."/job/{$jobName}/job/master/{$appBuildNumber}/stop";

        Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url);
    }

    private static function GetJenkinsApi($url)
    {
        return json_decode(Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(5)
            ->connectTimeout(2)
            ->get($url));
    }
}

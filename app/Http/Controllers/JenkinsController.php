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

    public function GetJob(Request $request, $job = null) : JsonResponse
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $jenkinsInfo = self::GetJenkinsJobResponse($this->baseUrl."/job/{$jobName}/api/json")->getData();

        return response()->json([
            'job' => collect($jenkinsInfo->job_info)->only(['name', 'url'])
        ]);
    }

    public function GetJobList() : JsonResponse
    {
        $jenkinsInfo = self::GetJenkinsJobResponse($this->baseUrl.'/api/json')->getData();

        return response()->json([
            'job_list' => collect($jenkinsInfo->job_info->jobs)->pluck('name')
        ]);
    }

    public function GetLastBuildSummary(Request $request, $job = null) //: JsonResponse
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $validatedResponse = collect(self::GetJenkinsJobResponse($this->baseUrl."/job/{$jobName}/job/master/api/json")->getData());

        // job doesn't exist.
        if (!$validatedResponse->get('jenkins_status') || !$validatedResponse->get('job_exists'))
        {
            return response()->json($validatedResponse->except('job_info'));
        }

        $response = collect();
        $response = $response->merge($validatedResponse->get('job_info')?->builds);

        // job exists, but builds are deleted or no build.
        // add nextBuildNumber value to build list for detailed info for job parametrization.
        if (isset($validatedResponse->get('job_info')->builds) && empty($validatedResponse->get('job_info')->builds))
        {
            $additionalBuildInfo = collect([
                '_class' => '',
                'number' => $validatedResponse->get('job_info')->nextBuildNumber,
                'url' => ''
            ]);

            $response = $response->add($additionalBuildInfo);
        }

        $buildList = collect([ 'build_list' => $response->last() ]);

        // copy jenkins params.
        $validatedResponse->map(function ($item, $key) use (&$buildList) {
            $buildList->put($key, $item);
        });

        return response()->json($buildList->except('job_info'));
    }

    public function GetLastBuildWithDetails(Request $request, $job = null) : JsonResponse
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $validatedResponse = collect(self::GetJenkinsJobResponse($this->baseUrl . "/job/{$jobName}/job/master/wfapi/runs")->getData());

        if (!$validatedResponse->get('jenkins_status') || !$validatedResponse->get('job_exists'))
        {
            return response()->json($validatedResponse->except('job_info'));
        }

        // builds exist.
        if (!empty($validatedResponse->get('job_info')))
        {
            $buildCollection = collect($validatedResponse->get('job_info'));
            $lastBuild = $buildCollection->first();
            $validatedResponse->put('job_url', $lastBuild->_links->self->href);

            $changeSetsResponse = self::GetJenkinsApi($this->baseUrl . "/job/{$jobName}/job/master/{$lastBuild->id}/api/json");
            $changeSets = isset($changeSetsResponse->changeSets[0])
                ? collect($changeSetsResponse->changeSets[0]->items)->pluck('msg')->reverse()->take(5)->values()
                : collect();

            $validatedResponse->put('change_sets', $changeSets);
            $validatedResponse->put('build_number', $lastBuild->id);

            $jobStages = collect($lastBuild->stages);
            $jobFailureStage = $jobStages->firstWhere('status', 'FAILED')?->name ?? '';

            $validatedResponse->put('build_status', collect([
                'status' => $lastBuild->status,
                'message' => $jobFailureStage
            ]));

            $validatedResponse->put('build_stage', $jobStages->last()?->name);
            $validatedResponse->put('timestamp', $lastBuild->startTimeMillis);
            $validatedResponse->put('estimated_duration', collect($validatedResponse->get('job_info'))->avg('durationMillis'));
        }

        return response()->json($validatedResponse->except('job_info'));
    }

    public function PostStopJob(Request $request, $job = null, $buildNumber = null) : void
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $appBuildNumber = is_null($buildNumber) ? $request->buildNumber : $buildNumber;

        $url = $this->baseUrl."/job/{$jobName}/job/master/{$appBuildNumber}/stop";

        Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url);
    }

    private static function GetJenkinsJobResponse($url) : JsonResponse
    {
        $response = collect([
            'jenkins_status' => false,
            'jenkins_message' => '',
            'job_exists' => false,
            'job_info' => collect()
        ]);

        $jenkinsResponse = '';

        try
        {
            $jenkinsResponse = self::GetJenkinsApi($url);
        }
        catch (\Exception $exception)
        {
            $response->put('jenkins_message', $exception->getMessage());

            return response()->json($response);
        }
        finally
        {
            $response->put('jenkins_status', true);
            $response->put('jenkins_message', 'success');

            $jobExists = !is_null($jenkinsResponse);
            if ($jobExists)
            {
                $response->put('job_exists', true);
                $response->put('job_info', collect($jenkinsResponse));
            }

            return response()->json($response);
        }
    }

    private static function GetJenkinsApi($url)
    {
        return json_decode(Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(20)
            ->connectTimeout(8)
            ->get($url));
    }
}

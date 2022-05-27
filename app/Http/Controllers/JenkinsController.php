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
        $jenkinsInfo = $this->GetJenkinsJobResponse($this->baseUrl."/job/{$jobName}/api/json")->getData();

        return response()->json([
            'job' => collect($jenkinsInfo->job_info)->only(['name', 'url'])
        ]);
    }

    public function GetJobList() : JsonResponse
    {
        $jenkinsInfo = $this->GetJenkinsJobResponse($this->baseUrl.'/api/json')->getData();

        return response()->json([
            'job_list' => collect($jenkinsInfo->job_info->jobs)->pluck('name')
        ]);
    }

    public function GetLastBuildSummary(Request $request, $job = null) : JsonResponse
    {
        $jobName = is_null($job) ? $request->projectName : $job;
        $jenkinsInfo = $this->GetJenkinsJobResponse($this->baseUrl."/job/{$jobName}/job/master/api/json")->getData();

        // job doesn't exists.
        if (!$jenkinsInfo)
        {
            return response()->json([
                'build_list' => null
            ]);
        }

        $response = collect();
        $response = $response->merge($jenkinsInfo->job_info->builds);

        // job exists, but builds are deleted. add nextBuildNumber value to build list for detailed info.
        if (isset($jenkinsInfo->job_info->builds) && empty($jenkinsInfo->job_info->builds))
        {
            $additionalBuildInfo = collect([
                '_class' => '',
                'number' => $jenkinsInfo->job_info->nextBuildNumber,
                'url' => ''
            ]);

            $response = $response->add($additionalBuildInfo);
        }

        return response()->json([
            'build_list' => $response->last()
        ]);
    }

    public function GetLastBuildWithDetails(Request $request, $job = null) //: JsonResponse
    {
        $jobName = is_null($job) ? $request->projectName : $job;

        $response = collect($this->GetJenkinsJobResponse($this->baseUrl . "/job/{$jobName}/job/master/wfapi/runs")->getData());
        $validatedResponse = $response->only([
            'jenkins_status',
            'jenkins_message',
            'job_exists',
            'job_info'
        ]);

        if (!$validatedResponse->get('jenkins_status') || !$validatedResponse->get('job_exists'))
        {
            return response()->json($validatedResponse->except('job_info'));
        }

        // builds exist.
        if (!empty($validatedResponse->get('job_info')))
        {
            $jobInfo = $validatedResponse->get('job_info')[0];

            $lastBuildNumber = $jobInfo->id;

            $validatedResponse->put('build_number', $lastBuildNumber);
            $validatedResponse->put('estimated_duration', $jobInfo->endTimeMillis);
            $validatedResponse->put('timestamp', $jobInfo->startTimeMillis);
            $validatedResponse->put('job_url', $jobInfo->_links->self->href);

            $changeSetsResponse = self::GetJenkinsApi($this->baseUrl . "/job/{$jobName}/job/master/{$lastBuildNumber}/api/json");
            $changeSets = isset($changeSetsResponse->changeSets[0])
                ? collect($changeSetsResponse->changeSets[0]->items)
                    ->pluck('msg')
                    ->reverse()
                    ->take(5)
                    ->values()
                : collect();

            $validatedResponse->put('change_sets', $changeSets);

            $jobStages = collect($jobInfo->stages);
            $jobFailureStage = $jobStages->firstWhere('status', 'FAILED')?->name ?? '';

            $validatedResponse->put('build_status', collect([
                'status' => $jobInfo->status,
                'message' => $jobFailureStage
            ]));

            $validatedResponse->put('build_stage', $jobStages->last()?->name);
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

    private function GetJenkinsJobResponse($url) : JsonResponse
    {
        $response = collect([
            'jenkins_status' => false,
            'jenkins_message' => '',
            'job_exists' => false,
            'job_info' => []
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

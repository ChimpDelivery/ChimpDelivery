<?php

namespace App\Http\Controllers;

use App\Models\AppInfo;

use App\Http\Requests\AppInfo\GetAppInfoRequest;
use App\Http\Requests\Jenkins\BuildRequest;
use App\Http\Requests\Jenkins\StopJobRequest;

use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

class JenkinsController extends Controller
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('jenkins.host').'/job/'.config('jenkins.ws');
    }

    public function GetJobList() : JsonResponse
    {
        $jenkinsResponse = $this->GetJenkinsJobResponse('/api/json')->getData();

        return response()->json([
            'job_list' => collect($jenkinsResponse->job_info->jobs)->pluck('name')
        ]);
    }

    public function GetJob(GetAppInfoRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));
        $jobResponse = $this->GetJenkinsJobResponse("/job/{$app->project_name}/api/json")->getData();

        return response()->json(collect($jobResponse->job_info)->only(['name', 'url']));
    }

    public function GetLastBuildSummary(GetAppInfoRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));

        $jobResponse = collect($this->GetJenkinsJobResponse("/job/{$app->project_name}/job/master/api/json")->getData());

        // job doesn't exist.
        if (!$jobResponse->get('jenkins_status') || !$jobResponse->get('job_exists')) {
            return response()->json($jobResponse->except('job_info'));
        }

        $response = collect();
        $response = $response->merge($jobResponse->get('job_info')?->builds);

        // job exists, but builds are deleted or no build.
        // add nextBuildNumber value to build list for detailed info for job parametrization.
        if (isset($jobResponse->get('job_info')->builds) && empty($jobResponse->get('job_info')->builds))
        {
            $additionalBuildInfo = collect([
                'number' => $jobResponse->get('job_info')->nextBuildNumber,
                'url' => ''
            ]);

            $response = $response->add($additionalBuildInfo);
        }

        $buildList = collect([ 'build_list' => collect($response->first())->only(['number', 'url']) ]);

        // copy jenkins params.
        $jobResponse->map(function ($item, $key) use (&$buildList) {
            $buildList->put($key, $item);
        });

        return response()->json($buildList->except('job_info'));
    }

    public function GetLastBuildWithDetails(GetAppInfoRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->id);

        $validatedResponse = collect($this->GetJenkinsJobResponse("/job/{$app->project_name}/job/master/wfapi/runs")->getData());

        if (!$validatedResponse->get('jenkins_status') || !$validatedResponse->get('job_exists')) {
            return response()->json($validatedResponse->except('job_info'));
        }

        // builds exist.
        if (!empty($validatedResponse->get('job_info')))
        {
            $buildCollection = collect($validatedResponse->get('job_info'));
            $lastBuild = $buildCollection->first();

            // get last build detail
            $lastBuildDetailResponse = collect($this->GetJenkinsJobResponse("/job/{$app->project_name}/job/master/{$lastBuild->id}/api/json")->getData());

            // add job url
            $validatedResponse->put('job_url', $lastBuild->_links->self->href);

            // add build number
            $validatedResponse->put('build_number', $lastBuild->id);

            // add commit history
            $changeSets = isset($lastBuildDetailResponse->get('job_info')->changeSets[0])
                ? collect($lastBuildDetailResponse->get('job_info')->changeSets[0]->items)->pluck('msg')->reverse()->take(5)->values()
                : collect();
            $validatedResponse->put('change_sets', $changeSets);

            // add job build detail
            $jobStages = collect($lastBuild->stages);
            $jobStopStage = $jobStages->whereIn('status', ['FAILED', 'ABORTED'])?->first()?->name ?? '';
            $jobStopStageDetail = $jobStages->whereIn('status', ['FAILED', 'ABORTED'])?->first()?->error?->message ?? '';

            $validatedResponse->put('build_status', collect([
                'status' => $lastBuild->status,
                'message' => $jobStopStage,
                'message_detail' => $jobStopStageDetail
            ]));

            // parameters[1] equals == platform
            $jobHasParameters = isset($lastBuildDetailResponse->get('job_info')->actions[0]->parameters);
            $buildPlatform = ($jobHasParameters) ? $lastBuildDetailResponse->get('job_info')->actions[0]?->parameters[1]?->value : 'Appstore';
            $validatedResponse->put('build_platform', $buildPlatform);

            $validatedResponse->put('build_stage', $jobStages->last()?->name);
            $validatedResponse->put('timestamp', $lastBuild->startTimeMillis);
            $validatedResponse->put('estimated_duration', collect($validatedResponse->get('job_info'))->avg('durationMillis'));
        }

        return response()->json($validatedResponse->except('job_info'));
    }

    public function BuildJob(BuildRequest $request) : JsonResponse
    {
        $validated = $request->validated();

        $app = AppInfo::find($validated['id']);

        $jobResponse = collect($this->GetLastBuildSummary($request)->getData());

        // job doesn't exist.
        if (!$jobResponse->get('jenkins_status') || !$jobResponse->get('job_exists')) {
            return response()->json($jobResponse);
        }

        // job exist but doesn't parameterized
        if ($jobResponse->get('build_list')->number == 1 && empty($jobResponse->get('build_list')->url))
        {
            Artisan::call("jenkins:default-trigger {$validated['id']}");
            return response()->json(['status' => "Project: {$app->project_name} building for first time. This build gonna be aborted by Jenkins!"]);
        }

        $validated['storeCustomVersion'] ??= 'false';
        $validated['storeBuildNumber'] = ($validated['storeCustomVersion'] == 'true') ? $validated['storeBuildNumber'] : 0;

        Artisan::call("jenkins:trigger {$validated['id']} master false {$validated['platform']} {$validated['storeVersion']} {$validated['storeCustomVersion']} {$validated['storeBuildNumber']}");
        return response()->json(['status' => "Project: {$app->project_name} building for {$validated['platform']}..."]);
    }

    public function StopJob(StopJobRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));
        $url = "/job/{$app->project_name}/job/master/{$request->validated('build_number')}/stop";

        return response()->json([
            'status' => Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($this->baseUrl . $url)->status()
        ]);
    }

    private function GetJenkinsJobResponse(string $url) : JsonResponse
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
            $jenkinsResponse = $this->GetJenkinsApi($this->baseUrl . $url);
        }
        catch (\Exception $exception)
        {
            $response->put('jenkins_message', $exception->getMessage());

            return response()->json($response);
        }

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

    private function GetJenkinsApi(string $url)
    {
        return json_decode(Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(20)
            ->connectTimeout(8)
            ->get($url));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Jenkins\GetJobBuilds;
use App\Http\Controllers\Controller;

use App\Http\Requests\Jenkins\BuildRequest;
use App\Http\Requests\Jenkins\StopJobRequest;

use App\Models\AppInfo;

use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class JenkinsController extends Controller
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('jenkins.host') . '/job/' . Auth::user()->workspace->githubSetting->organization_name;
    }

    public function StopJob(StopJobRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));
        $this->authorize('abort', $app);

        $url = "/job/{$app->project_name}/job/master/{$request->validated('build_number')}/stop";

        return response()->json([
            'status' => Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($this->baseUrl . $url)->status()
        ]);
    }
}

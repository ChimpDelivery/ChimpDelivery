<?php

namespace App\Services;

use App\Models\GithubSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class JenkinsService
{
    // Note: Jenkins workspace name specified by connected organization name on Github.
    // Job and organization names are unique.
    private GithubSetting $githubSetting;

    public function __construct(Request $request)
    {
        $this->githubSetting = $request->expectsJson()
            ? Auth::user()->githubSetting
            : Auth::user()->workspace->githubSetting;

        $this->baseUrl = config('jenkins.host')
            .'/job/'
            .$this->githubSetting->organization_name;
    }

    public function GetResponse(string $url) : mixed
    {
        return $this->TryJenkinsRequest($url)->getData();
    }

    private function TryJenkinsRequest(string $url) : JsonResponse
    {
        $jenkinsResponse = '';

        try
        {
            $jenkinsResponse = $this->GetRequest($this->baseUrl . $url);
        }
        catch (\Exception $exception)
        {
            return response()->json([
                'exception_message' => $exception->getMessage(),
                'jenkins_status' => $exception->getCode(),
                'jenkins_data' => null,
            ]);
        }

        return response()->json($jenkinsResponse);
    }

    private function GetRequest(string $url) : array
    {
        $request = Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(20)
            ->connectTimeout(8)
            ->get($url);

        $isTunnelOffline = $request->header('Ngrok-Error-Code');

        return [
            'jenkins_status' => ($isTunnelOffline) ? 3200 : $request->status(),
            'jenkins_data' => ($isTunnelOffline) ? null : json_decode($request),
        ];
    }
}


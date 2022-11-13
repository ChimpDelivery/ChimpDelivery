<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Models\GithubSetting;

class JenkinsService
{
    // Note: Jenkins workspace name specified by connected organization name on Github.
    // Job and organization names are unique.
    private readonly GithubSetting $githubSetting;

    // organization workspace
    private readonly string $baseUrl;

    //
    private readonly int $targetWorkspaceId;

    public function __construct()
    {
        $isWebUser = Auth::guard('web')->check();

        $this->githubSetting = ($isWebUser)
            ? Auth::user()->workspace->githubSetting
            : Auth::user()->githubSetting;

        $this->baseUrl = config('jenkins.host')
            .'/job/'
            .$this->githubSetting->organization_name;

        $this->targetWorkspaceId = ($isWebUser)
            ? Auth::user()->workspace->id
            : Auth::user()->id;
    }

    public function GetWorkspaceId() : int
    {
        return $this->targetWorkspaceId;
    }

    public function GetResponse(string $url) : mixed
    {
        return $this->TryJenkinsRequest($url, 'get')->getData();
    }

    public function PostResponse(string $url) : mixed
    {
        return $this->TryJenkinsRequest($url, 'post')->getData();
    }

    private function TryJenkinsRequest(string $url, string $method) : JsonResponse
    {
        $jenkinsResponse = '';

        try
        {
            if (in_array($method, ['post', 'get']))
            {
                if ($method == 'get')
                {
                    $jenkinsResponse = $this->GetRequest($this->baseUrl . $url);
                }
                else
                {
                    $jenkinsResponse = $this->PostRequest($this->baseUrl . $url);
                }
            }
            else
            {
                throw new \Exception('Request method not supported!');
            }
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
        $request = $this->GetJenkinsUser()->get($url);
        return $this->GetJenkinsResponse($request);
    }

    private function PostRequest(string $url) : array
    {
        $request = $this->GetJenkinsUser()->post($url);
        return $this->GetJenkinsResponse($request);
    }

    private function GetJenkinsResponse($request) : array
    {
        $isTunnelOffline = $request->header('Ngrok-Error-Code');

        return [
            'jenkins_status' => ($isTunnelOffline) ? 3200 : $request->status(),
            'jenkins_data' => ($isTunnelOffline) ? null : json_decode($request),
        ];
    }

    private function GetJenkinsUser() : PendingRequest
    {
        return Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
            ->timeout(20)
            ->connectTimeout(8);
    }
}


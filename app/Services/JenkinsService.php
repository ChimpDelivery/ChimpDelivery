<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Models\GithubSetting;

class JenkinsService
{
    // credentials
    private readonly string $jenkinsWorkspaceUrl;
    private readonly string $jenkinsUser;
    private readonly string $jenkinsToken;

    // Note: Jenkins workspace name specified by connected organization name on Github.
    // Job and organization names are unique.
    private readonly GithubSetting $githubSetting;

    public function __construct(string $url, string $user, string $token)
    {
        $this->githubSetting = Auth::user()->workspace->githubSetting;

        $this->jenkinsWorkspaceUrl = implode('/', [
            $url,
            'job',
            $this->githubSetting->organization_name
        ]);

        $this->jenkinsUser = $user;
        $this->jenkinsToken = $token;
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
                $jenkinsResponse = $this->$method($this->jenkinsWorkspaceUrl . $url);
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

    private function get(string $url) : array
    {
        $request = $this->GetJenkinsUser()->get($url);
        return $this->GetJenkinsResponse($request);
    }

    private function post(string $url) : array
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
        return Http::withBasicAuth($this->jenkinsUser, $this->jenkinsToken)
            ->timeout(20)
            ->connectTimeout(8);
    }
}


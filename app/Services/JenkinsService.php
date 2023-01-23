<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class JenkinsService
{
    public function __construct(
        private readonly string $tunnelUrl,
        private readonly string $jenkinsUser,
        private readonly string $jenkinsToken,
    ) { }

    // {tunnel_url}/job/{organization_folder}
    public function GetWorkspaceUrl() : string
    {
        return implode('/', [
            $this->tunnelUrl,
            'job',
            Auth::user()->orgName(),
        ]);
    }

    public function GetJenkinsUser() : PendingRequest
    {
        return Http::withBasicAuth($this->jenkinsUser, $this->jenkinsToken)
            ->timeout(20)
            ->connectTimeout(8);
    }

    public function GetResponse(string $url, bool $isHtml = false) : mixed
    {
        return $this->TryJenkinsRequest($url, 'get', $isHtml)->getData();
    }

    public function PostResponse(string $url, bool $isHtml = false) : mixed
    {
        return $this->TryJenkinsRequest($url, 'post', $isHtml)->getData();
    }

    private function TryJenkinsRequest(string $url, string $method, bool $isHtml) : JsonResponse
    {
        $jenkinsResponse = '';

        try
        {
            if (in_array($method, ['post', 'get']))
            {
                $jenkinsResponse = $this->$method($this->GetWorkspaceUrl() . $url, $isHtml);
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

    private function get(string $url, bool $isHtml) : array
    {
        $request = $this->GetJenkinsUser()->get($url);
        return $this->GetJenkinsResponse($request, $isHtml);
    }

    private function post(string $url, bool $isHtml) : array
    {
        $request = $this->GetJenkinsUser()->post($url);
        return $this->GetJenkinsResponse($request, $isHtml);
    }

    private function GetJenkinsResponse(Response $request, bool $isHtml) : array
    {
        $isTunnelOffline = $request->header('Ngrok-Error-Code');

        return [
            'jenkins_status' => ($isTunnelOffline)
                ? 3200
                : $request->status(),

            'jenkins_data' => ($isTunnelOffline)
                ? null
                : ($isHtml ? $request->body() : json_decode($request)),
        ];
    }
}

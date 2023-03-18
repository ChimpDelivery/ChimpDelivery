<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

use App\Models\User;

class JenkinsService
{
    public function __construct(
        private readonly string $tunnelUrl,
        private readonly string $jenkinsUser,
        private readonly string $jenkinsToken,
        private ?User $user = null,
    ) { }

    public function SetUser(User $user) : self
    {
        $this->user = $user;
        return $this;
    }

    public function GetWorkspaceUrl() : string
    {
        $orgName = $this->user?->orgName() ?? Auth::user()->orgName();
        return "{$this->tunnelUrl}/job/{$orgName}";
    }

    public function GetHttpClient() : PendingRequest
    {
        return Http::withBasicAuth($this->jenkinsUser, $this->jenkinsToken)
            ->timeout(20)
            ->connectTimeout(8);
    }

    public function GetResponse(string $url, bool $isHtml = false) : \stdClass
    {
        return $this->MakeJenkinsRequest('get', $url, $isHtml)->getData();
    }

    public function PostResponse(string $url, bool $isHtml = false) : \stdClass
    {
        return $this->MakeJenkinsRequest('post', $url, $isHtml)->getData();
    }

    private function MakeJenkinsRequest(string $method, string $url, bool $isHtml) : JsonResponse
    {
        try
        {
            $requestUrl = $this->GetWorkspaceUrl() . $url;
            $jenkinsResponse = $this->GetParsedResponse($method, $requestUrl, $isHtml);
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

    private function GetParsedResponse(string $method, string $url, bool $isHtml) : array
    {
        $request = $this->GetHttpClient()->{$method}($url);
        return $this->ParseJenkinsResponse($request, $isHtml);
    }

    private function ParseJenkinsResponse(Response $request, bool $isHtml) : array
    {
        $isTunnelOffline = $request->header('Ngrok-Error-Code');

        return [
            'jenkins_status' => ($isTunnelOffline) ? 3200 : $request->status(),
            'jenkins_data' => ($isTunnelOffline) ? null : ($isHtml ? $request->body() : json_decode($request)),
        ];
    }
}

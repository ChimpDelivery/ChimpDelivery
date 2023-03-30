<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\PendingRequest;

use App\Models\User;

/// Handles Jenkins authentication and requests
/// Request response contains jenkins_status and jenkins_data properties.
/// All data served from Jenkins is in jenkins_data property.
class JenkinsService
{
    public function __construct(
        private readonly string $jenkinsMasterUrl,
        private readonly string $jenkinsUsername,
        private readonly string $jenkinsUserToken,
        private readonly ?User $user = null,
    ) {
    }

    public function GetWorkspaceUrl() : string
    {
        $orgName = $this->user?->orgName() ?? Auth::user()->orgName();
        return "{$this->jenkinsMasterUrl}/job/{$orgName}";
    }

    public function GetHttpClient() : PendingRequest
    {
        return Http::withBasicAuth($this->jenkinsUsername, $this->jenkinsUserToken)
            ->timeout(config('jenkins.timeout'))
            ->connectTimeout(config('jenkins.connect_timeout'));
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

    private function ParseJenkinsResponse(Response $response, bool $isHtml) : array
    {
        // check tunneled-agent connection
        $isTunnelOff = $response->header(config('tunnel.error_header'));

        // return request data as a plain text or json
        $method = $isHtml ? 'body' : 'json';

        return [
            'jenkins_status' => ($isTunnelOff) ? config('tunnel.error_status') : $response->status(),
            'jenkins_data' => ($isTunnelOff) ? null : $response->{$method}(),
        ];
    }
}

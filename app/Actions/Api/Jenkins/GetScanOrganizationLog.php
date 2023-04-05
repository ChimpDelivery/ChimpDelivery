<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

use App\Services\JenkinsService;

class GetScanOrganizationLog
{
    use AsAction;

    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) {
    }

    public function handle() : array
    {
        $response = $this->jenkinsService->GetResponse("/computation/consoleText", true);

        return [
            'log_title' => Auth::user()->workspace->name . '| Scan Log',
            'full_log' => $response->jenkins_data ?? ''
        ];
    }

    public function htmlResponse(array $response) : View
    {
        return view('log')->with($response);
    }

    public function jsonResponse(array $response) : JsonResponse
    {
        return response()->json($response);
    }

    public function authorize() : bool
    {
        return Auth::user()->can('scan jobs');
    }
}

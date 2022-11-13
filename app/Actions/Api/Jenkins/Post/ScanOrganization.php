<?php

namespace App\Actions\Api\Jenkins\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Events\AppChanged;
use App\Services\JenkinsService;
use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

class ScanOrganization extends BaseJenkinsAction
{
    public function handle(Request $request) : array
    {
        $response = app(JenkinsService::class)->PostResponse("/build?delay=0");

        $isResponseSucceed = $response->jenkins_status == Response::HTTP_OK;
        $responseMessage = ($isResponseSucceed)
            ? 'Repository scanning begins.'
            : "Repository scanning could not run! Error Code: {$response->jenkins_status}";

        return [
            'success' => $isResponseSucceed,
            'message' => $responseMessage,
        ];
    }

    public function asListener(AppChanged $event)
    {
        $this->handle($event->request);
    }

    public function authorize(Request $request) : bool
    {
        return $request->expectsJson()
            ? true
            : Auth::user()->can('scan jobs');
    }
}

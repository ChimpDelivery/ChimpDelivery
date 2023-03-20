<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Services\JenkinsService;

class GetJobs
{
    use AsAction;

    public function __construct(
        private readonly JenkinsService $jenkinsService
    ) { }

    public function handle() : JsonResponse
    {
        $jobResponse = $this->jenkinsService->GetResponse('/api/json');
        $jobResponse->jenkins_data = collect($jobResponse->jenkins_data?->jobs)->pluck('name');

        return response()->json($jobResponse);
    }
}

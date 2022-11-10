<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

use App\Models\AppInfo;
use App\Http\Requests\Jenkins\StopJobRequest;

class StopJob
{
    use AsAction;

    public function StopJob(StopJobRequest $request) : JsonResponse
    {
        $app = AppInfo::find($request->validated('id'));

        $url = "/job/{$app->project_name}/job/master/{$request->validated('build_number')}/stop";

        return response()->json([
            'status' => Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))
                ->post($this->baseUrl . $url)
                ->status()
        ]);
    }
}

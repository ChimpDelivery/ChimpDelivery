<?php

namespace App\Actions\Api\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

use App\Models\AppInfo;
use App\Http\Requests\Jenkins\BuildRequest;

class BuildJob
{
    use AsAction;

    public function handle(BuildRequest $request) : RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        $app = AppInfo::find($validated['id']);

        $jobResponse = GetJobBuilds::run($request)->getData();
        $firstBuild = $jobResponse->jenkins_data[0];

        // job exist but doesn't parameterized in Jenkins
        if ($firstBuild->number == 1 && empty($firstBuild->url))
        {
            Artisan::call("jenkins:default-trigger {$validated['id']}");
            $responseMessage = "Project: {$app->project_name} building for first time. This build gonna be aborted by Jenkins!";

            // api response
            if ($request->expectsJson())
            {
                return response()->json([
                    'status' => $responseMessage
                ]);
            }

            // web response
            return back()->with('success', $responseMessage);
        }

        $validated['store_custom_version'] ??= 'false';
        $validated['store_build_number'] = ($validated['store_custom_version'] == 'true')
            ? ($validated['store_build_number'] ?? 1)
            : 0;

        Artisan::call("jenkins:trigger {$validated['id']} master false {$validated['platform']} {$validated['store_version']} {$validated['store_custom_version']} {$validated['store_build_number']}");

        // api response
        $responseMessage = "Project: <b>{$app->project_name}</b> building for <b>{$validated['platform']}</b>...";
        if ($request->expectsJson())
        {
            return response()->json([
                'status' => $responseMessage
            ]);
        }

        // web response
        return back()->with('success', $responseMessage);
    }
}

<?php

namespace App\Actions\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;

use App\Http\Requests\Jenkins\BuildRequest;

use App\Http\Controllers\Api\JenkinsController;

class BuildApp
{
    use AsAction;

    public function handle(BuildRequest $request) : RedirectResponse
    {
        $jenkinsController = app(JenkinsController::class);

        return back()->with('success', $jenkinsController->BuildJob($request)->getData()->status);
    }
}

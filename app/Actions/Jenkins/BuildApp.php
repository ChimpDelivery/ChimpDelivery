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
        session()->flash('success', $jenkinsController->BuildJob($request)->getData()->status);
        return back();
    }
}

<?php

namespace App\Actions\Dashboard;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;

use App\Http\Controllers\Api\AppInfoController;
use App\Http\Requests\AppInfo\UpdateAppInfoRequest;

class UpdateApp
{
    use AsAction;

    public function handle(UpdateAppInfoRequest $request) : RedirectResponse
    {
        $response = app(AppInfoController::class)->UpdateApp($request);
        session()->flash('success', "Project: <b>{$response->getData()->project_name}</b> updated.");

        return to_route('index');
    }
}

<?php

namespace App\Actions\Dashboard;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;

use App\Http\Controllers\Api\AppInfoController;

use App\Http\Requests\AppInfo\GetAppInfoRequest;

class DeleteApp
{
    use AsAction;

    public function handle(GetAppInfoRequest $request) : RedirectResponse
    {
        $appInfoController = app(AppInfoController::class);
        return to_route('index')->with('success', $appInfoController->DeleteApp($request)->getData()->message);
    }
}

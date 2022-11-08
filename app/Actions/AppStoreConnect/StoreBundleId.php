<?php

namespace App\Actions\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;

use App\Http\Requests\AppStoreConnect\StoreBundleRequest;

use App\Http\Controllers\Api\AppStoreConnectController;

class StoreBundleId
{
    use AsAction;

    public function handle(StoreBundleRequest $request) : RedirectResponse
    {
        $response = app(AppStoreConnectController::class)->CreateBundle($request)->getData();
        if (isset($response->status->errors))
        {
            $error = $response->status->errors[0];

            return to_route('create_bundle')
                ->withErrors([ 'bundle_id' => $error->detail . " (Status code: {$error->status})" ])
                ->withInput();
        }

        session()->flash('success', 'Bundle: <b>' . $request->validated('bundle_id') . '</b> created!');
        return to_route('index');
    }
}

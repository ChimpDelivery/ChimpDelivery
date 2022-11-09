<?php

namespace App\Actions\Api\Packages;

use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\Package\UpdatePackageRequest;

use App\Models\Package;

class UpdatePackage
{
    use AsAction;

    public function handle(UpdatePackageRequest $request) : JsonResponse
    {
        $response = Package::where('package_id', '=', $request->validated('package_id'))
            ->update([ 'hash' => $request->validated('hash') ]);

        return response()->json(['status' => $response], Response::HTTP_ACCEPTED);
    }

    public function authorize()
    {
        return Auth::user()->hasRole('Admin_Super');
    }
}

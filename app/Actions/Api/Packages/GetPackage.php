<?php

namespace App\Actions\Api\Packages;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\Package\GetPackageRequest;

use App\Models\Package;

class GetPackage
{
    use AsAction;

    public function handle(GetPackageRequest $request) : JsonResponse
    {
        $response = Package::where('package_id', '=', $request->validated('package_id'))
            ->select(['package_id', 'url', 'hash'])
            ->firstOrNew();

        return response()->json($response, Response::HTTP_ACCEPTED);
    }
}

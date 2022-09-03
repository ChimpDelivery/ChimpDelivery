<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\Package\GetPackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;

use App\Models\Package;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PackageController extends Controller
{
    public function GetPackages() : JsonResponse
    {
        return response()->json(['packages' => Package::all(['package_id', 'url', 'hash'])->values()]);
    }

    public function GetPackage(GetPackageRequest $request) : JsonResponse
    {
        $response = Package::where('package_id', '=', $request->validated('package_id'))
            ->select(['package_id', 'url', 'hash'])
            ->firstOrNew();

        return response()->json($response, Response::HTTP_ACCEPTED);
    }

    public function UpdatePackage(UpdatePackageRequest $request) : JsonResponse
    {
        $response = Package::where('package_id', '=', $request->validated('package_id'))
            ->update([ 'hash' => $request->validated('hash') ]);

        return response()->json(['status' => $response], Response::HTTP_ACCEPTED);
    }
}

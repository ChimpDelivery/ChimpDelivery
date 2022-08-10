<?php

namespace App\Http\Controllers;

use App\Models\Package;

use App\Http\Requests\Package\GetPackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PackageController extends Controller
{
    public function GetPackage(GetPackageRequest $request) : JsonResponse
    {
        $response = Package::where('package_id', '=', $request->validated('package_id'))->select(['package_id', 'url', 'hash'])->firstOrNew();

        return response()->json($response, Response::HTTP_ACCEPTED);
    }

    public function GetPackages() : JsonResponse
    {
        return response()->json(['packages' => Package::all(['package_id', 'url', 'hash'])->values()]);
    }

    public function UpdatePackage(UpdatePackageRequest $request) : JsonResponse
    {
        $packageId = $request->validated('package_id');

        $response = Package::where('package_id', '=', $packageId)->update([
            'hash' => $request->hash
        ]);

        return response()->json([
            'message' => ($response) ? "{$packageId} updated successfully!" : "{$packageId} can not found!",
            'status' => ($response) ? Response::HTTP_ACCEPTED : Response::HTTP_FORBIDDEN
        ]);
    }
}

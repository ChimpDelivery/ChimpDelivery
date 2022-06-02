<?php

namespace App\Http\Controllers;

use App\Models\Package;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PackageController extends Controller
{
    public function GetPackage(Request $request) : JsonResponse
    {
        $response = Package::where('package_id', '=', $request->id)->firstOrNew();

        return response()->json($response, Response::HTTP_ACCEPTED);
    }

    public function UpdatePackage(Request $request) : JsonResponse
    {
        $response = Package::where('package_id', '=', $request->id)->update([
            'hash' => $request->hash
        ]);

        return response()->json([
            'status' => ($response) ? Response::HTTP_ACCEPTED : Response::HTTP_FORBIDDEN
        ]);
    }
}

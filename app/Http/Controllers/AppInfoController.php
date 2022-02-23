<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AppInfoController extends Controller
{
    public function index() : JsonResponse
    {
        return response()->json([
            'status_code' => 404
        ]);
    }

    public function show($id) : JsonResponse
    {
        return response()->json([
            'app_name' => $id,
            'app_bundle' => $id,
            'fb_app_id' => $id,
            'elephant_id' => $id,
            'elephant_secret' => $id
        ]);
    }
}

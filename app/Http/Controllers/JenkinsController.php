<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JenkinsController extends Controller
{
    public function TriggerJenkinsPipeline(Request $request) : JsonResponse
    {
        $request->validate([
            'user' => 'required',
            'pass' => 'required',
            'pipeline' => 'required',
            'token' => 'required'
        ]);

        $url = "http://192.168.0.40:8080/job/$request->pipeline/build?token=$request->token";

        $response = Http::withBasicAuth($request->user, $request->pass)->get($url);

        return response()->json([
            'status_code' => $response->status()
        ]);
    }
}

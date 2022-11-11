<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait AsActionResponse
{
    public function htmlResponse(array $response) : RedirectResponse
    {
        if ($response['success'])
        {
            return back()->with('success', $response['message']);
        }

        return back()->withErrors($response['message']);
    }

    public function jsonResponse(array $response) : JsonResponse
    {
        return response()->json($response);
    }
}

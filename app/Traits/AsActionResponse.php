<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait AsActionResponse
{
    public function htmlResponse(array $response) : RedirectResponse
    {
        $message = $response['message'];
        $hasRedirect = isset($response['redirect']);
        $redirect = !empty($response['redirect']) ? $response['redirect'] : 'index';

        if ($response['success'])
        {
            return ($hasRedirect)
                ? to_route($redirect)->with('success', $message)
                : back()->with('success', $message);
        }

        return ($hasRedirect)
            ? to_route($redirect)->withErrors($message)
            : back()->withErrors($message);
    }

    public function jsonResponse(array $response) : JsonResponse
    {
        return response()->json($response);
    }
}

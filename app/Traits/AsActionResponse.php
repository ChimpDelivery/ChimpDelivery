<?php

namespace App\Traits;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait AsActionResponse
{
    public function htmlResponse(array $response) : RedirectResponse
    {
        $isResponseSucceed = $response['success'];
        $message = $response['message'];
        $hasRedirect = isset($response['redirect']);
        $redirectRouteName = $hasRedirect && !empty($response['redirect'])
            ? $response['redirect']
            : 'index';

        if ($isResponseSucceed)
        {
            if ($hasRedirect)
            {
                return to_route($redirectRouteName)->with('success', $message);
            }

            return back()->with('success', $message);
        }

        if ($hasRedirect)
        {
            return to_route($redirectRouteName)->withErrors($message);
        }

        return back()->withErrors($message);
    }

    public function jsonResponse(array $response) : JsonResponse
    {
        return response()->json($response);
    }
}

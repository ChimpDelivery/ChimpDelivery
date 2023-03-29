<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserNotNew
{
    public function handle(Request $request, \Closure $next)
    {
        if (Auth::user()->isNew())
        {
            return response()->json([
                'message' => 'Forbidden: Only Workspace Users!',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

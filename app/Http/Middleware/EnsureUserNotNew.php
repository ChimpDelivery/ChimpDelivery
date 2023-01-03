<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnsureUserNotNew
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->isNew())
        {
            return response()->json([
                'message' => 'Forbidden: Only Workspace Users!'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

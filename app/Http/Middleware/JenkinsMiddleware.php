<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JenkinsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($response->status() != 200) {
            return response()->json([
                'jenkins_status_code' => $response->status()
            ]);
        }

        return $next($request);
    }
}

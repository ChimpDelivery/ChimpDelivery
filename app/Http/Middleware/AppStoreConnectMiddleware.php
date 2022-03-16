<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AppStoreConnectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->headers->get('api_key');
        if (!$apiKey) {
            return response()->json([
                'appstore_status' => 'Api Key required!'
            ]);
        }

        $token = User::where('api_token', $apiKey)->first();
        if (!$token) {
            return response()->json([
                'appstore_status' => 'Api Key not found!'
            ]);
        }

        return $next($request);
    }
}

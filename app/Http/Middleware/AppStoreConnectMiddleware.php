<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;

class AppStoreConnectMiddleware
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
        if (!$request->hasHeader('api-key'))
        {
            return response()->json(['server_response' => 'Api Key required!'],
                Response::HTTP_FORBIDDEN);
        }

        $token = User::where('api_token', $request->header('api-key'))->first();
        if (!$token)
        {
            return response()->json(['server_response' => 'Api Key not found!'],
                Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

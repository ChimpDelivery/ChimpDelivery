<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class);
        $middleware->web(\Illuminate\Session\Middleware\StartSession::class);
        $middleware->web(\Illuminate\View\Middleware\ShareErrorsFromSession::class);
        $middleware->web(\Illuminate\Routing\Middleware\SubstituteBindings::class);

        $middleware->api(\Illuminate\Routing\Middleware\SubstituteBindings::class);
        $middleware->api('throttle:api');
        $middleware->api(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);

        $middleware->alias([
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'optimizeImages' => \Spatie\LaravelImageOptimizer\Middlewares\OptimizeImages::class,
            'ensureUserNotNew' => \App\Http\Middleware\EnsureUserNotNew::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

    })->create();

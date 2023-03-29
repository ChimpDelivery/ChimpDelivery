<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Sentry\Laravel\Integration;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'private_key',
        'issuer_id',
        'kid',
        'app_specific_pass',
        'personal_access_token',
        'invite_code',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register() : void
    {
        $this->renderable(function (NotFoundHttpException $exception, $request) {
            if ($request->expectsJson())
            {
                return response()->json([ 'message' => 'Object not found!' ], Response::HTTP_NOT_FOUND);
            }
        });

        $this->reportable(function (\Throwable $e) {
            Integration::captureUnhandledException($e);
        });

        // permission exception
        // https://spatie.be/docs/laravel-permission/v5/advanced-usage/exceptions
    }
}

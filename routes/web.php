<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Mail\Markdown;

use Spatie\Health\Http\Controllers\HealthCheckResultsController;

//// main route
Route::get('/', fn () => to_route('login'));

////
Route::get('/promotion', fn () => view('dashboard-promotion'));

//// contracts
Route::get('/privacy', fn () => Markdown::parse(File::get(resource_path('markdown/privacy.md'))))
    ->name('privacy');

Route::get('/terms', fn () => Markdown::parse(File::get(resource_path('markdown/terms.md'))))
    ->name('terms');

//// super-admin routes
Route::get('health', HealthCheckResultsController::class)
    ->middleware([ 'auth', 'verified', 'can:viewHealth' ])
    ->name('health');

require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';

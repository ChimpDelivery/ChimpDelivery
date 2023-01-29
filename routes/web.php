<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Mail\Markdown;

use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('/', fn() => to_route('login'));

////
Route::get('/promotion', fn() => view('dashboard-promotion'));

////
Route::get('/privacy', fn() => Markdown::parse(File::get(resource_path('markdown/privacy.md'))))
    ->name('privacy');

Route::get('/terms', fn() => Markdown::parse(File::get(resource_path('markdown/terms.md'))))
    ->name('terms');

////
Route::middleware([ 'auth', 'verified', 'can:viewHealth' ])->group(function () {
    Route::get('health', HealthCheckResultsController::class)
        ->name('health');
});

require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';

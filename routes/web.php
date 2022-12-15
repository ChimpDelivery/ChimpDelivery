<?php

use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('/', fn() => view('welcome'));

Route::middleware(['auth', 'verified', 'role:Admin_Super'])->group(function () {
    Route::get('health', HealthCheckResultsController::class);
});

require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';

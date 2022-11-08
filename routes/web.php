<?php

use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('health', HealthCheckResultsController::class)->middleware('role:Admin_Super');
});

require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';

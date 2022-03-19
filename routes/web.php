<?php

use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('health', HealthCheckResultsController::class)->middleware('auth');

require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';

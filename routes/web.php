<?php

use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('/', fn() => to_route('login'));
Route::get('/promotion', fn() => view('dashboard-promotion'));

Route::middleware(['auth', 'verified', 'role:Admin_Super'])->group(function () {
    Route::get('health', HealthCheckResultsController::class)->name('health');
});

require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';

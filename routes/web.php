<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/breezedash', function () {
    return view('breezedash');
})->middleware(['auth'])->name('breezedash');

require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';

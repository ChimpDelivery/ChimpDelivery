<?php

use App\Http\Controllers\AppInfoController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::controller(AppInfoController::class)->group(function () {
    Route::get('/api/appinfo', 'index');
    Route::get('/api/appinfo/{id}', 'show');
    Route::post('/api/appinfo', 'store');
    Route::get('/api/appinfo/create', action: 'create');
    Route::get('/api/appinfo/{id}/edit', 'edit');
    Route::put('/api/appinfo/{id}', 'update');
    Route::delete('/api/appinfo/{id}', 'destroy');
});

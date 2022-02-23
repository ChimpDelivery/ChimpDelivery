<?php

use App\Http\Controllers\AppInfoController;
use App\Http\Controllers\DashboardController;

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


Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index');
    Route::get('/dashboard/add-app-info', 'create');
    Route::get('/dashboard/update-app-info/{id}', 'select');
    Route::post('/dashboard/update-app-info/{id}/update', 'update');
});

Route::post('/dashboard/store-app-info', [AppInfoController::class, 'store']);
Route::put('/dashboard/store-app-info/{id}', [AppInfoController::class, 'update']);

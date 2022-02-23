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
    Route::get('/dashboard', 'index')->name('get_app_list');
    Route::get('/dashboard/add-app-info', 'create')->name('add_app_info');
    Route::get('/dashboard/update-app-info/{id}', 'select')->name('get_app_info');
    Route::post('/dashboard/update-app-info/{id}/update', 'update')->name('update_app_info');
    Route::post('/dashboard/delete-app-info/{id}', 'delete')->name('delete_app_info');
});

Route::post('/dashboard/store-app-info', [AppInfoController::class, 'store']);

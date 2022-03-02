<?php

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

    // main route.
    Route::get('/dashboard', 'Index')->middleware('doNotCacheResponse')->name('get_app_list');

    // get and post routes to create app info data.
    Route::get('/dashboard/add-app-info', 'CreateApp')->middleware('doNotCacheResponse')->name('add_app_info');
    Route::post('/dashboard/store-app-info', 'StoreApp')->middleware('doNotCacheResponse');

    // get and post routes to update app info data.
    Route::get('/dashboard/update-app-info/{id}', 'SelectApp')->middleware('doNotCacheResponse')->name('get_app_info');
    Route::post('/dashboard/update-app-info/{id}/update', 'UpdateApp')->middleware('doNotCacheResponse')->name('update_app_info');

    Route::get('/dashboard/build-app/{id}', 'BuildApp')->middleware('doNotCacheResponse');

    // post route to delete app info data.
    Route::post('/dashboard/delete-app-info/{id}', 'DeleteApp')->middleware('doNotCacheResponse')->name('delete_app_info');

    //
    Route::get('/dashboard/clear-cache', 'ClearCache')->middleware('doNotCacheResponse');
});

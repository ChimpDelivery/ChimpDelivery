<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


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


Route::controller(DashboardController::class)->middleware('auth')->group(function () {
    // main route.
    Route::get('/dashboard', 'Index')->name('get_app_list');

    // get and post routes to create app info data.
    Route::get('/dashboard/add-app-info', 'CreateAppForm')->name('add_app_info');
    Route::post('/dashboard/store-app-info', 'StoreAppForm');

    // get and post routes to update app info data.
    Route::get('/dashboard/update-app-info/{id}', 'SelectApp')->name('get_app_info');
    Route::post('/dashboard/update-app-info/{id}/update', 'UpdateApp')->name('update_app_info');

    Route::get('/dashboard/build-app/{id}', 'BuildApp');

    // post route to delete app info data.
    Route::post('/dashboard/delete-app-info/{id}', 'DeleteApp')->name('delete_app_info');

    //
    Route::get('/dashboard/clear-cache', 'ClearCache');
});

Route::get('/breezedash', function () {
    return view('breezedash');
})->middleware(['auth'])->name('breezedash');

require __DIR__.'/auth.php';

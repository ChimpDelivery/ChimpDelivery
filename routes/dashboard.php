<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::controller(DashboardController::class)->middleware(['auth', 'verified'])->group(function () {

    // main route.
    Route::get('/dashboard', 'Index')->name('get_app_list');

    // get and post routes to create app info data.
    Route::get('/dashboard/add-app-info', 'CreateAppForm')->name('add_app_info');
    Route::post('/dashboard/store-app-info', 'StoreAppForm');

    // get and post routes to update app info data.
    Route::get('/dashboard/update-app-info', 'SelectApp')->name('get_app_info');
    Route::post('/dashboard/update-app-info', 'UpdateApp')->name('update_app_info');

    // post route to delete app info data.
    Route::post('/dashboard/delete-app-info', 'DeleteApp')->name('delete_app_info');

    // jenkins bridge.
    Route::get('/dashboard/build-app', 'BuildApp');
    Route::get('/dashboard/stop-job', 'StopJob');
    Route::get('/dashboard/scan-repo', 'ScanRepo');

    //
    Route::get('/dashboard/create-bundle', 'CreateBundleForm')->name('create_bundle');
    Route::post('/dashboard/store-bundle', 'StoreBundleForm');
});

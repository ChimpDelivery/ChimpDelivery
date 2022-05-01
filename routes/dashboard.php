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
    Route::get('/dashboard/update-app-info/{id}', 'SelectApp')->name('get_app_info');
    Route::post('/dashboard/update-app-info/{id}/update', 'UpdateApp')->name('update_app_info');

    Route::get('/dashboard/build-app/{id}/{isWorkspace}/{tfVersion}/{buildNumber}', 'BuildApp');
    Route::get('/dashboard/stop-job/{projectName}/{buildNumber}', 'StopJob');
    Route::get('/dashboard/scan-repo', 'ScanRepo');

    // post route to delete app info data.
    Route::post('/dashboard/delete-app-info/{id}', 'DeleteApp')->name('delete_app_info');

    Route::get('/dashboard/create-bundle', 'CreateBundleForm')->name('create_bundle');
    Route::post('/dashboard/store-bundle', 'StoreBundleForm');

    //
    Route::get('/dashboard/clear-cache', 'ClearCache');
});

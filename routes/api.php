<?php

use App\Http\Controllers\AppInfoController;
use App\Http\Controllers\AppStoreConnectController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AppInfoController::class)->group(function() {
    // active endpoints
    Route::get('appinfo/{id}', 'show');

    // not-implementeds
    Route::get('appinfo', 'index');
    Route::post('appinfo', 'store');
    Route::get('appinfo/create', 'create');
    Route::get('appinfo/{id}/edit', 'edit');
    Route::put('appinfo/{id}', 'update');
    Route::delete('appinfo/{id}', 'destroy');
});

Route::controller(AppStoreConnectController::class)->group(function() {
    // active endpoints
    Route::get('appstoreconnect/get-token', 'GetToken');
    Route::get('appstoreconnect/get-full-info', 'GetFullAppInfo');
    Route::get('appstoreconnect/get-app-list', 'GetAppList');
    Route::get('appstoreconnect/get-all-bundles', 'GetAllBundles');
    Route::get('appstoreconnect/get-build-list', 'GetBuildList');
    Route::get('appstoreconnect/clear-cache', 'ClearCache');
});

Route::controller(\App\Http\Controllers\JenkinsController::class)->group(function() {
    // active endpoints
    Route::post('jenkins/trigger-pipeline', 'TriggerJenkinsPipeline');
});



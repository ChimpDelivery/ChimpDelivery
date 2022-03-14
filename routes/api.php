<?php

use App\Http\Controllers\AppInfoController;
use App\Http\Controllers\AppStoreConnectController;
use App\Http\Controllers\JenkinsController;

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
    Route::get('appinfo/{id}', 'Show');

    // not-implementeds
    Route::get('appinfo', 'Index');
    Route::post('appinfo', 'Store');
    Route::get('appinfo/create', 'Create');
    Route::get('appinfo/{id}/edit', 'Edit');
    Route::put('appinfo/{id}', 'Update');
    Route::delete('appinfo/{id}', 'Destroy');
});

Route::controller(AppStoreConnectController::class)->group(function() {
    // active endpoints
    Route::get('appstoreconnect/get-token', 'GetToken');
    Route::get('appstoreconnect/get-full-info', 'GetFullAppInfo');
    Route::get('appstoreconnect/get-app-list', 'GetAppList');
    Route::get('appstoreconnect/get-app-list/{projectName}', 'GetSpecificApp');
    Route::get('appstoreconnect/get-all-bundles', 'GetAllBundles');
    Route::get('appstoreconnect/get-build-list', 'GetBuildList');
    Route::get('appstoreconnect/create-bundle', 'CreateBundle');
    Route::get('appstoreconnect/clear-cache', 'ClearCache');
});

Route::controller(JenkinsController::class)->group(function() {
    // active endpoints
    Route::get('jenkins/get-job-list', 'GetJobList');
    Route::get('jenkins/get-job/{projectName}', 'GetJob');
    Route::get('jenkins/get-build-list/{projectName}', 'GetBuildList');
    Route::get('jenkins/get-latest-build-number/{projectName}', 'GetLatestBuildNumber');
    Route::get('jenkins/get-latest-build-info/{projectName}/{buildNumber}', 'GetLatestBuildInfo');
    Route::get('jenkins/stop-job/{projectName}/{buildNumber}', 'PostStopJob');
});

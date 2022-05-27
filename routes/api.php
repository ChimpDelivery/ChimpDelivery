<?php

use App\Http\Controllers\AppStoreConnectController;
use App\Http\Controllers\GithubController;
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

Route::get('appstoreconnect/get-app-list/{id}', 'App\Http\Controllers\AppInfoController@GetApp')->middleware('appstore');
Route::get('apps/get-app-list/{id}', 'App\Http\Controllers\AppInfoController@GetApp')->middleware('appstore');

Route::controller(AppStoreConnectController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('appstoreconnect/get-token', 'GetToken');
    Route::get('appstoreconnect/get-full-info', 'GetFullAppInfo');
    Route::get('appstoreconnect/get-app-list', 'GetAppList');
    Route::get('appstoreconnect/get-build-list', 'GetBuildList');
    Route::get('appstoreconnect/create-bundle', 'CreateBundle');
});

Route::controller(JenkinsController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('jenkins/get-job/{projectName}', 'GetJob');
    Route::get('jenkins/get-job-list', 'GetJobList');
    Route::get('jenkins/get-build-list/{projectName}', 'GetLastBuildSummary');
    Route::get('jenkins/get-latest-build-info/{projectName}', 'GetLastBuildWithDetails');
    Route::get('jenkins/stop-job/{projectName}/{buildNumber}', 'PostStopJob');
});

Route::controller(GithubController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('github/get-repositories', 'GetRepositories');
    Route::get('github/get-repository/{id}', 'GetRepository');
    Route::get('github/create-repository/{projectName}/{projectDescription}', 'CreateRepository');
});

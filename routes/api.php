<?php

use App\Http\Controllers\AppInfoController;
use App\Http\Controllers\AppStoreConnectController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\JenkinsController;
use App\Http\Controllers\PackageController;

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

// dashboard apps
Route::controller(AppInfoController::class)->middleware('appstore')->group(function ()
{
    Route::get('apps/get-app', 'GetApp');
});

// package management
Route::controller(PackageController::class)->middleware('appstore')->group(function ()
{
    Route::get('packages/get-package', 'GetPackage');
    Route::get('packages/get-packages', 'GetPackages');
    Route::post('packages/update-package', 'UpdatePackage');
});

// appstore connect
Route::controller(AppStoreConnectController::class)->middleware('auth:sanctum')->group(function ()
{
    Route::get('appstoreconnect/get-token', 'GetToken');
    Route::get('appstoreconnect/get-full-info', 'GetFullAppInfo');
    Route::get('appstoreconnect/get-app-list', 'GetAppList');
    Route::get('appstoreconnect/get-build-list', 'GetBuildList');

    Route::post('appstoreconnect/create-bundle', 'CreateBundle');
});

// jenkins
Route::controller(JenkinsController::class)->middleware('auth:sanctum')->group(function ()
{
    Route::get('jenkins/get-job', 'GetJob');
    Route::get('jenkins/get-job-list', 'GetJobList');
    Route::get('jenkins/get-build-list', 'GetLastBuildSummary');
    Route::get('jenkins/get-latest-build-info', 'GetLastBuildWithDetails');

    Route::post('jenkins/build-job', 'BuildJob');
    Route::post('jenkins/stop-job', 'StopJob');
});

// github
Route::controller(GithubController::class)->middleware('auth:sanctum')->group(function ()
{
    Route::get('github/get-repositories', 'GetRepositories');
    Route::get('github/get-repository', 'GetRepository');

    Route::post('github/create-repository', 'CreateRepository');
});

<?php

use App\Actions\Api\Github\GetRepositories;
use App\Actions\Api\Github\GetRepository;
use App\Actions\Api\Github\CreateRepository;

use App\Http\Controllers\Api\AppInfoController;
use App\Http\Controllers\Api\AppStoreConnectController;
use App\Http\Controllers\Api\JenkinsController;
use App\Http\Controllers\Api\PackageController;

use Illuminate\Support\Facades\Route;

// dashboard apps
Route::controller(AppInfoController::class)->middleware('auth:sanctum')->group(function () {

    Route::get('apps/get-app', 'GetApp');
    Route::post('apps/create-app', 'CreateApp');
    Route::post('apps/update-app', 'UpdateApp');
});

// appstore connect
Route::controller(AppStoreConnectController::class)->middleware('auth:sanctum')->group(function () {

    Route::get('appstoreconnect/get-token', 'GetToken');
    Route::get('appstoreconnect/get-full-info', 'GetFullAppInfo');
    Route::get('appstoreconnect/get-app-list', 'GetAppList');
    Route::get('appstoreconnect/get-build-list', 'GetBuildList');

    Route::post('appstoreconnect/create-bundle', 'CreateBundle');
});

// jenkins
Route::controller(JenkinsController::class)->middleware('auth:sanctum')->group(function () {

    Route::get('jenkins/get-job', 'GetJob');
    Route::get('jenkins/get-job-list', 'GetJobList');
    Route::get('jenkins/get-job-builds', 'GetJobBuilds');
    Route::get('jenkins/get-job-lastbuild', 'GetJobLastBuild');

    Route::post('jenkins/build-job', 'BuildJob');
    Route::post('jenkins/stop-job', 'StopJob');
});

// github
Route::middleware('auth:sanctum')->group(function () {

    Route::get('github/get-repositories', GetRepositories::class);
    Route::get('github/get-repository', GetRepository::class);

    Route::post('github/create-repository', CreateRepository::class);
});

// package management
Route::controller(PackageController::class)->middleware('auth:sanctum')->group(function () {

    Route::get('packages/get-packages', 'GetPackages');
    Route::get('packages/get-package', 'GetPackage');

    Route::post('packages/update-package', 'UpdatePackage');
});

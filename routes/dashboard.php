<?php

use Illuminate\Support\Facades\Route;

use App\Actions\Dashboard\GetIndexForm;
use App\Actions\Dashboard\StoreApp;
use App\Actions\Dashboard\CreateAppForm;
use App\Actions\Dashboard\UpdateApp;
use App\Actions\Dashboard\UpdateAppForm;
use App\Actions\Dashboard\DeleteApp;

use App\Actions\Workspace\GetWorkspaceForm;
use App\Actions\Workspace\StoreWorkspace;
use App\Actions\Workspace\GetJoinWorkspaceForm;
use App\Actions\Workspace\JoinWorkspace;
use App\Actions\Workspace\CreateWorkspaceApiKey;

use App\Actions\AppStoreConnect\CreateBundleIdForm;
use App\Actions\AppStoreConnect\StoreBundleId;

use App\Actions\Jenkins\StopJob;
use App\Actions\Jenkins\BuildApp;
use App\Actions\Jenkins\ScanOrganization;

Route::middleware(['auth', 'verified'])->group(function () {

    //////////////////////////
    //// index route
    //////////////////////////
    Route::get('/dashboard', GetIndexForm::class)->name('index');

    ////////////////////////////////
    //// workspace routes
    ////////////////////////////////
    Route::get('/dashboard/workspace-settings', GetWorkspaceForm::class)
        ->name('workspace_settings')
        ->middleware('permission:view workspace');

    Route::post('/dashboard/workspace-settings', StoreWorkspace::class)
        ->middleware('permission:create workspace|update workspace');

    Route::post('/dashboard/create-workspace-api-key', CreateWorkspaceApiKey::class)
        ->middleware('permission:update workspace');

    Route::get('/dashboard/workspace-join', GetJoinWorkspaceForm::class)
        ->name('workspace_join')
        ->middleware('permission:join workspace');

    Route::post('/dashboard/workspace-join', JoinWorkspace::class)
        ->middleware('permission:join workspace');

    //////////////////////////////
    //// app info routes
    //////////////////////////////
    Route::get('/dashboard/add-app-info', CreateAppForm::class)
        ->name('add_app_info')
        ->middleware('permission:create app');

    Route::post('/dashboard/store-app-info', StoreApp::class)
        ->name('store_app_info')
        ->middleware('permission:update app');

    Route::get('/dashboard/update-app-info', UpdateAppForm::class)
        ->name('get_app_info')
        ->middleware('permission:update app');

    Route::post('/dashboard/update-app-info', UpdateApp::class)
        ->name('update_app_info')
        ->middleware('permission:update app');

    Route::post('/dashboard/delete-app-info', DeleteApp::class)
        ->name('delete_app_info')
        ->middleware('permission:delete app');

    ////////////////////////////
    //// jenkins routes
    ///////////////////////////
    Route::post('/dashboard/build-app', BuildApp::class)
        ->middleware('permission:build job');

    Route::get('/dashboard/stop-job', StopJob::class)
        ->middleware('permission:abort job');

    Route::get('/dashboard/scan-repo', ScanOrganization::class)
        ->middleware('permission:scan jobs');

    //////////////////////////////////
    //// app store connect routes
    /////////////////////////////////
    Route::get('/dashboard/create-bundle', CreateBundleIdForm::class)
        ->name('create_bundle')
        ->middleware('permission:create bundle');

    Route::post('/dashboard/store-bundle', StoreBundleId::class)
        ->middleware('permission:create bundle');
});

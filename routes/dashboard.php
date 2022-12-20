<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Actions\Api\Apps\DeleteAppInfo;
use App\Actions\Api\Apps\GetAppInfo;
use App\Actions\Api\Apps\StoreAppInfo;

use App\Actions\Api\AppStoreConnect\CreateBundleId;

use App\Actions\Api\Ftp\CreateGooglePrivacy;
use App\Actions\Api\Ftp\CreateFBAppAds;

use App\Actions\Api\Jenkins\GetJobLastBuildLog;
use App\Actions\Api\Jenkins\Post\AbortJob;
use App\Actions\Api\Jenkins\Post\BuildJob;
use App\Actions\Api\Jenkins\Post\ScanOrganization;

use App\Actions\Dashboard\User\UpdateUserProfile;

use App\Actions\Dashboard\Workspace\GetWorkspaceIndex;
use App\Actions\Dashboard\Workspace\CreateAppForm;
use App\Actions\Dashboard\Workspace\GetWorkspaceForm;
use App\Actions\Dashboard\Workspace\JoinWorkspace;
use App\Actions\Dashboard\Workspace\StoreWorkspace;

Route::middleware(['auth', 'verified'])->group(function () {

    //////////////////////////
    //// main routes
    //////////////////////////
    Route::get('/dashboard',
        fn() => Auth::user()->isNew()
            ? view('workspace-settings')->with([ 'isNew' => true ])
            : GetWorkspaceIndex::run()
    )->name('index');

    Route::get('/dashboard/profile', fn() => view('user-profile')->with([
        'isNewUser' => Auth::user()->isNew()
    ]))->name('dashboard.profile');

    Route::post('/dashboard/profile', UpdateUserProfile::class);

    ////////////////////////////////
    //// workspace routes
    ////////////////////////////////
    Route::get('/dashboard/workspace-settings', GetWorkspaceForm::class)
        ->name('workspace_settings')
        ->middleware('permission:view workspace');

    Route::post('/dashboard/workspace-settings', StoreWorkspace::class)
        ->middleware('permission:create workspace|update workspace');

    Route::get('/dashboard/workspace-join', fn() => view('workspace-join'))
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

    Route::post('/dashboard/store-app-info', StoreAppInfo::class)
        ->name('store_app_info')
        ->middleware('permission:update app')
        ->middleware('optimizeImages');

    Route::get('/dashboard/update-app-info', GetAppInfo::class)
        ->name('get_app_info')
        ->middleware('permission:update app');

    Route::post('/dashboard/update-app-info', StoreAppInfo::class)
        ->name('update_app_info')
        ->middleware('permission:update app')
        ->middleware('optimizeImages');

    Route::post('/dashboard/delete-app-info', DeleteAppInfo::class)
        ->name('delete_app_info')
        ->middleware('permission:delete app');

    ////////////////////////////
    //// jenkins routes
    ///////////////////////////
    Route::post('/dashboard/build-app', BuildJob::class)
        ->middleware('permission:build job')
        ->name('build-app');

    Route::get('/dashboard/build-log', GetJobLastBuildLog::class)
        ->middleware('permission:view job log');

    Route::get('/dashboard/abort-job', AbortJob::class)
        ->middleware('permission:abort job');

    Route::post('/dashboard/workspace/scan-jobs', ScanOrganization::class)
        ->middleware('permission:scan jobs')
        ->name('scan-workspace-jobs');

    //////////////////////////////////
    //// app store connect routes
    /////////////////////////////////
    Route::get('/dashboard/create-bundle', fn() => view('create-bundle-form'))
        ->name('create_bundle')
        ->middleware('permission:create bundle');

    Route::post('/dashboard/store-bundle', CreateBundleId::class)
        ->middleware('permission:create bundle');


    ///////////////////////////
    /// talus specific
    //////////////////////////
    Route::post('/dashboard/create-privacy', CreateGooglePrivacy::class)
        ->name('create_privacy')
        ->middleware('permission:update app');

    Route::post('/dashboard/fb-app-ads', CreateFBAppAds::class)
        ->name('fb_app_ads')
        ->middleware('permission:update app');
});

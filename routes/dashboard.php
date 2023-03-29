<?php

use Illuminate\Support\Facades\Route;

use Spatie\Honeypot\ProtectAgainstSpam;

use App\Actions\Api\Apps\DeleteAppInfo;
use App\Actions\Api\Apps\GetAppInfo;
use App\Actions\Api\Apps\StoreAppInfo;
use App\Actions\Api\AppStoreConnect\CreateBundleId;
use App\Actions\Api\Jenkins\GetJobLastBuildLog;
use App\Actions\Api\Jenkins\Post\AbortJob;
use App\Actions\Api\Jenkins\Post\BuildJob;
use App\Actions\Api\Jenkins\Post\ScanOrganization;
use App\Actions\Api\Github\GetUserOrganizations;

use App\Actions\Dashboard\User\JoinWorkspace;
use App\Actions\Dashboard\User\UpdateUserProfile;
use App\Actions\Dashboard\Workspace\CreateAppForm;
use App\Actions\Dashboard\Workspace\GetWorkspaceIndex;
use App\Actions\Dashboard\Workspace\StoreWorkspace;

Route::middleware([ 'auth:sanctum', 'verified', 'ensureUserNotNew', ProtectAgainstSpam::class ])->group(function () {
    //////////////////////////
    //// main routes
    //////////////////////////
    Route::get(
        '/dashboard',
        fn () => auth()->user()->isNew()
        ? to_route('workspace_settings')
        : GetWorkspaceIndex::run()
    )->name('index')
     ->withoutMiddleware('ensureUserNotNew');

    Route::get('/dashboard/profile', fn () => view('user-profile', [ 'user' => auth()->user() ]))
        ->name('dashboard.profile')
        ->withoutMiddleware('ensureUserNotNew');

    Route::post('/dashboard/profile', UpdateUserProfile::class)
        ->withoutMiddleware('ensureUserNotNew');

    Route::get('/dashboard/workspace-join', fn () => view('workspace-join'))
        ->name('workspace_join')
        ->middleware('permission:join workspace')
        ->withoutMiddleware('ensureUserNotNew');

    Route::post('/dashboard/workspace-join', JoinWorkspace::class)
        ->middleware('permission:join workspace')
        ->withoutMiddleware('ensureUserNotNew');

    ////////////////////////////////
    //// workspace routes
    ////////////////////////////////
    Route::get(
        '/dashboard/workspace-settings',
        fn () => view('workspace-settings', [
            'isNew' => auth()->user()->isNew(),
            'workspace' => auth()->user()->workspace,
            'workspace_github_orgs' => GetUserOrganizations::run(),
        ])
    )->name('workspace_settings')
     ->middleware('permission:create workspace|view workspace|update workspace')
     ->withoutMiddleware('ensureUserNotNew');

    Route::post('/dashboard/workspace-settings', StoreWorkspace::class)
        ->middleware('permission:create workspace|update workspace')
        ->withoutMiddleware('ensureUserNotNew');

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
    Route::get('/dashboard/create-bundle', fn () => view('create-bundle-form'))
        ->name('create_bundle')
        ->middleware('permission:create bundle');

    Route::post('/dashboard/store-bundle', CreateBundleId::class)
        ->middleware('permission:create bundle');
});

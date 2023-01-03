<?php

use Illuminate\Support\Facades\Route;

use App\Actions\Api\Apps\GetAppInfo;
use App\Actions\Api\Apps\StoreAppInfo;

use App\Actions\Api\AppStoreConnect\CreateBundleId;
use App\Actions\Api\AppStoreConnect\CreateToken;
use App\Actions\Api\AppStoreConnect\GetAppList;
use App\Actions\Api\AppStoreConnect\GetBuildList;
use App\Actions\Api\AppStoreConnect\GetFullAppInfo;

use App\Actions\Api\Github\GetRepositories;
use App\Actions\Api\Github\GetRepository;

use App\Actions\Api\Jenkins\GetJob;
use App\Actions\Api\Jenkins\GetJobBuilds;
use App\Actions\Api\Jenkins\GetJobLastBuild;
use App\Actions\Api\Jenkins\GetJobLastBuildLog;
use App\Actions\Api\Jenkins\GetJobs;
use App\Actions\Api\Jenkins\Post\AbortJob;
use App\Actions\Api\Jenkins\Post\BuildJob;
use App\Actions\Api\Jenkins\Post\ScanOrganization;

use App\Actions\Api\S3\Provision\GetCertificate;
use App\Actions\Api\S3\Provision\GetProvisionProfile;

///////////////////////
// apps
//////////////////////
Route::middleware(['auth:sanctum', 'verified', 'ensureUserNotNew'])->group(function () {

    Route::get('apps/get-app', GetAppInfo::class);
    Route::post('apps/create-app', StoreAppInfo::class);
    Route::post('apps/update-app', StoreAppInfo::class);

    // Route::post('apps/delete-app', DeleteAppInfo::class);
});

/////////////////////////
// appstore connect api
////////////////////////
Route::middleware(['auth:sanctum', 'verified', 'ensureUserNotNew'])->group(function () {

    Route::get('appstoreconnect/get-token', CreateToken::class);
    Route::get('appstoreconnect/get-full-info', GetFullAppInfo::class);
    Route::get('appstoreconnect/get-app-list', GetAppList::class);
    Route::get('appstoreconnect/get-build-list', GetBuildList::class);

    // s3 related
    Route::get('appstoreconnect/get-cert', GetCertificate::class);
    Route::get('appstoreconnect/get-provision-profile', GetProvisionProfile::class);

    Route::post('appstoreconnect/create-bundle', CreateBundleId::class);
});

////////////////////
// jenkins api
////////////////////
Route::middleware(['auth:sanctum', 'verified', 'ensureUserNotNew'])->group(function () {

    Route::get('jenkins/get-job', GetJob::class);
    Route::get('jenkins/get-jobs', GetJobs::class);
    Route::get('jenkins/get-job-builds', GetJobBuilds::class);
    Route::get('jenkins/get-job-lastbuild', GetJobLastBuild::class);
    Route::get('jenkins/get-job-lastbuild-log', GetJobLastBuildLog::class);

    Route::post('jenkins/build-job', BuildJob::class);
    Route::post('jenkins/abort-job', AbortJob::class);
    Route::post('jenkins/scan-organization', ScanOrganization::class);
});

////////////////////////
// github api
//////////////////////
Route::middleware(['auth:sanctum', 'verified', 'ensureUserNotNew'])->group(function () {

    Route::get('github/get-repositories', GetRepositories::class);
    Route::get('github/get-repository', GetRepository::class);
});

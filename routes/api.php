<?php

use Illuminate\Support\Facades\Route;

use App\Actions\Api\Apps\GetAppInfo;
use App\Actions\Api\Apps\StoreAppInfo;

use App\Services\AppStoreConnectService;
use App\Actions\Api\AppStoreConnect\CreateBundleId;
use App\Actions\Api\AppStoreConnect\GetAppList;
use App\Actions\Api\AppStoreConnect\GetStoreApps;

use App\Actions\Api\Github\GetRepositories;
use App\Actions\Api\Github\GetRepository;
use App\Actions\Api\Github\GetRepositoryBranches;

use App\Actions\Api\Jenkins\GetJob;
use App\Actions\Api\Jenkins\GetJobBuilds;
use App\Actions\Api\Jenkins\GetJobLastBuild;
use App\Actions\Api\Jenkins\GetJobLastBuildLog;
use App\Actions\Api\Jenkins\GetJobs;
use App\Actions\Api\Jenkins\Post\AbortJob;
use App\Actions\Api\Jenkins\Post\BuildJob;
use App\Actions\Api\Jenkins\ScanOrganization;
use App\Actions\Api\Jenkins\GetScanOrganizationLog;

use App\Actions\Api\S3\Provision\GetCertificate;
use App\Actions\Api\S3\Provision\GetProvisionProfile;

///////////////////////
// apps
//////////////////////
Route::middleware([ 'auth:sanctum' ])->group(function () {
    Route::get('apps/get-app', GetAppInfo::class);
    Route::post('apps/create-app', StoreAppInfo::class);
    Route::post('apps/update-app', StoreAppInfo::class);

    // Route::post('apps/delete-app', DeleteAppInfo::class);
});

/////////////////////////
// appstore connect api
////////////////////////
Route::middleware([ 'auth:sanctum' ])->group(function () {
    //
    Route::get('appstoreconnect/get-token', fn () => app(AppStoreConnectService::class)->CreateToken());
    Route::get('appstoreconnect/get-store-apps', GetStoreApps::class);
    Route::get('appstoreconnect/get-app-list', GetAppList::class);

    Route::post('appstoreconnect/create-bundle', CreateBundleId::class);

    // s3 related
    Route::get('appstoreconnect/get-cert', GetCertificate::class);
    Route::get('appstoreconnect/get-provision-profile', GetProvisionProfile::class);
});

////////////////////
// jenkins api
////////////////////
Route::middleware([ 'auth:sanctum' ])->group(function () {
    Route::get('jenkins/get-job', GetJob::class);
    Route::get('jenkins/get-jobs', GetJobs::class);
    Route::get('jenkins/get-job-builds', GetJobBuilds::class);
    Route::get('jenkins/get-job-lastbuild', GetJobLastBuild::class);
    Route::get('jenkins/get-job-lastbuild-log', GetJobLastBuildLog::class);
    Route::get('jenkins/get-scan-organization-log', GetScanOrganizationLog::class);
    Route::post('jenkins/build-job', BuildJob::class);
    Route::post('jenkins/abort-job', AbortJob::class);
    Route::post('jenkins/scan-organization', ScanOrganization::class);
});

////////////////////////
// github api
//////////////////////
Route::middleware([ 'auth:sanctum' ])->group(function () {
    Route::get('github/get-repositories', GetRepositories::class);
    Route::get('github/get-repository', GetRepository::class);
    Route::get('github/get-repository-branches', GetRepositoryBranches::class);
});

<?php

use App\Http\Controllers\AppInfoController;
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


Route::controller(AppInfoController::class)->group(function () {

    Route::get('appstoreconnect/get-token', 'getToken');
    Route::get('appstoreconnect/get-full-info', 'getFullInfo');
    Route::get('appstoreconnect/get-app-list', 'getAppList');
    Route::get('appstoreconnect/get-app-dictionary', 'getAppDictionary');
    Route::get('appstoreconnect/get-all-bundles', 'getAllBundles');
    Route::get('appstoreconnect/clear-cache', 'clearCache');

    // active endpoints
    Route::get('appinfo/{id}', 'show');

    // not-implementeds
    Route::get('appinfo', 'index');
    Route::post('appinfo', 'store');
    Route::get('appinfo/create', action: 'create');
    Route::get('appinfo/{id}/edit', 'edit');
    Route::put('appinfo/{id}', 'update');
    Route::delete('appinfo/{id}', 'destroy');
});



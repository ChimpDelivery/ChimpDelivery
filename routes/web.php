<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/api/appinfo/{id}', function ($id) {
    return response()->json([
        'app_name' => $id,
        'app_bundle' => $id,
        'fb_app_id' => $id,
        'elephant_id' => $id,
        'elephant_secret' => $id
    ]);
});

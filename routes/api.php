<?php

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

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::name('api.')->group(function () {
        Route::apiResource('posts', 'App\Http\Controllers\PostApiController');

        Route::apiResource('comments', 'App\Http\Controllers\CommentApiController')->only([
            'store', 'update', 'destroy'
        ]);

        Route::apiResource('tags', 'App\Http\Controllers\TagApiController')->only([
            'index', 'store', 'update', 'destroy'
        ]);
    });
});

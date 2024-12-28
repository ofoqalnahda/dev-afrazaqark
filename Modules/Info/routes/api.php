<?php

use Illuminate\Support\Facades\Route;
use Modules\Info\App\Http\Controllers\Api\InfoController;

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

Route::prefix('v1/info')->name('api.')->group(function () {
    Route::get('/fqa', [InfoController::class,'FQA']);
    Route::get('/{slug}', [InfoController::class,'Show']);

});

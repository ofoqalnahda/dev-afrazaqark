<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Info\App\Http\Controllers\Dashboard\FaqController;
use Modules\Info\App\Http\Controllers\Dashboard\InfoController;

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


Route::prefix('v1/dashboard')->name('api.')->group(function () {

    #########################################################
    #                         faqs                          #
    #########################################################
    Route::post('/faqs/{id}', [FaqController::class,'update']);
    Route::resource('/faqs', FaqController::class);


    #########################################################
    #                         infos                         #
    #########################################################
    Route::get('info/{slug}', [InfoController::class,'Show']);
    Route::post('info/{slug}', [InfoController::class,'update']);



});

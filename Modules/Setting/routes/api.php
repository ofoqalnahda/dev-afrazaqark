<?php

use Modules\Setting\App\Http\Controllers\Api\IconController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Setting\App\Http\Controllers\Api\AreaController;
use Modules\Setting\App\Http\Controllers\Api\CityController;
use Modules\Setting\App\Http\Controllers\Api\ContactUsController;
use Modules\Setting\App\Http\Controllers\Api\SettingController;

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

Route::prefix('v1')->name('api.')->group(function () {
    #########################################################
    #                         areas                         #
    #########################################################
        Route::resource('/areas', AreaController::class);
        Route::post('/areas/{id}', [AreaController::class,'update']);

    #########################################################
    #                         cities                        #
    #########################################################
    Route::resource('/cities', CityController::class);
    Route::post('/cities/{id}', [CityController::class,'update']);


    #########################################################
    #                         icons                         #
    #########################################################
    Route::resource('/icons', IconController::class);
    Route::post('/icons/{id}', [IconController::class,'update']);

    #########################################################
    #                         settings                         #
    #########################################################
    Route::get('/get-settings', [SettingController::class,'index']);
    Route::post('/update-settings', [SettingController::class,'update']);

    Route::post('/contact-us', [ContactUsController::class,'store']);

});

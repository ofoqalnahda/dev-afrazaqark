<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Setting\App\Http\Controllers\Dashboard\AreaController;
use Modules\Setting\App\Http\Controllers\Dashboard\CityController;
use Modules\Setting\App\Http\Controllers\Dashboard\IconController;
use Modules\Setting\App\Http\Controllers\Dashboard\OfferController;
use Modules\Setting\App\Http\Controllers\Dashboard\SettingController;
use Modules\Setting\App\Http\Controllers\Dashboard\SliderController;

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
    #                         areas                         #
    #########################################################
    Route::post('/areas/update-status/{id}', [AreaController::class,'updateStatus']);
    Route::post('/areas/{id}', [AreaController::class,'update']);
    Route::resource('/areas', AreaController::class);



    #########################################################
    #                         cities                         #
    #########################################################
    Route::post('/cities/update-status/{id}', [CityController::class,'updateStatus']);
    Route::post('/cities/{id}', [CityController::class,'update']);
    Route::resource('/cities', CityController::class);

    #########################################################
    #                         sliders                         #
    #########################################################
    Route::post('/sliders/update-status/{id}', [SliderController::class,'updateStatus']);
    Route::post('/sliders/{id}', [SliderController::class,'update']);
    Route::resource('/sliders', SliderController::class);


    #########################################################
    #                         icons                         #
    #########################################################
    Route::post('/icons/update-status/{id}', [IconController::class,'updateStatus']);
    Route::post('/icons/{id}', [IconController::class,'update']);
    Route::resource('/icons', IconController::class);


    #########################################################
    #                        offers                         #
    #########################################################
    Route::post('/offers/update-status/{id}', [OfferController::class,'updateStatus']);
    Route::post('/offers/{id}', [OfferController::class,'update']);
    Route::resource('/offers', OfferController::class);

    #########################################################
    #                         settings                         #
    #########################################################
    Route::get('/get-settings', [SettingController::class,'index']);
    Route::post('/update-settings', [SettingController::class,'update']);

});

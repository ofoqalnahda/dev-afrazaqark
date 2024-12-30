<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Customer\App\Http\Controllers\Dashboard\CustomerController;
use Modules\Setting\App\Http\Controllers\Dashboard\AreaController;
use Modules\Setting\App\Http\Controllers\Dashboard\CityController;
use Modules\Setting\App\Http\Controllers\Dashboard\IconController;
use Modules\Setting\App\Http\Controllers\Dashboard\OfferController;
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
    #                       Customer Api                    #
    #########################################################


    Route::get('customers/index-deleted', [CustomerController::class, 'IndexDeleted']);
    Route::get('customers/restore/{id}', [CustomerController::class, 'restore']);
    Route::post('/customers/update-status/{id}', [CustomerController::class,'updateStatus']);
    Route::post('/customers/{id}', [CustomerController::class,'update']);
    Route::resource('/customers', CustomerController::class);

});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\Apis\AuthController;

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


    #######################################
    ##             AUTH Routs            ##
    #######################################
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::post('forget-password', [AuthController::class, 'forgetPassword']);
    Route::post('resend-code', [AuthController::class, 'SendCode']);
    Route::post('check-code', [AuthController::class, 'checkCode']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::delete('remove-account', [AuthController::class, 'removeAccount']);
    Route::get('active-remove-account', [AuthController::class, 'ActiveRemoveAccount']);




});

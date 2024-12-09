<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\App\Http\Controllers\Apis\AuthController;

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

Route::prefix('v1/dashboard')->group(function () {


    #######################################
    ##             AUTH Routs            ##
    #######################################
    Route::post('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::post('forget-password', [AuthController::class, 'forgetPassword']);
    Route::post('resend-code', [AuthController::class, 'SendCode']);
    Route::post('check-code', [AuthController::class, 'checkCode']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::get('get-my-permission', [AuthController::class, 'getMyPermission']);
    Route::get('get-all-permission', [AuthController::class, 'getAllPermission']);






});

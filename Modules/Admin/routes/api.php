<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\App\Http\Controllers\Apis\AdminController;
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


    #######################################
    ##             Profile Routs         ##
    #######################################
    Route::get('get-profile', [AuthController::class, 'getProfile']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::post('update-account', [AuthController::class, 'update']);


    #######################################
    ##             Admins Routs          ##
    #######################################
    Route::get('get-my-permission', [AuthController::class, 'getMyPermission']);
    Route::get('get-all-permission', [AuthController::class, 'getAllPermission']);

    Route::post('admins/check-password', [AdminController::class, 'checkPassword']);
    Route::get('admins/index-deleted', [AdminController::class, 'IndexDeleted']);
    Route::get('admins/restore/{id}', [AdminController::class, 'restore']);
    Route::post('admins/update-status/{id}', [AdminController::class, 'updateStatus']);
    Route::post('admins/{id}', [AdminController::class, 'update']);
    Route::resource('admins', AdminController::class);




});

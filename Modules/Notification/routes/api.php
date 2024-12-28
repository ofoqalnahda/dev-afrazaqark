<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Notification\App\Http\Controllers\Api\NotificationController;

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

Route::prefix('v1/notifications')->name('api.')->group(function () {
    Route::get('/', [NotificationController::class,'index']);
    Route::get('/count', [NotificationController::class,'count']);

});

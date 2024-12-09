<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Sort\App\Http\Controllers\Api\BankController;
use Modules\Sort\App\Http\Controllers\Api\CancellationReasonController;
use Modules\Sort\App\Http\Controllers\Api\OperationTypeController;
use Modules\Sort\App\Http\Controllers\Api\PropertyTypeController;
use Modules\Sort\App\Http\Controllers\Api\RouteTypeController;
use Modules\Sort\App\Http\Controllers\Api\TransactionController;
use Modules\Sort\App\Http\Controllers\Api\TransactionStatusController;

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




    Route::get('/get-count-with-status', [TransactionController::class,'GetCount']);
    Route::get('/get-my-transactions', [TransactionController::class,'index']);
    Route::Post('transactions/step-1', [TransactionController::class,'StepOne']);
    Route::Post('transactions/step-2', [TransactionController::class,'StepTwo']);
    Route::Post('transactions/payment-transaction', [TransactionController::class,'PaymentTransaction']);




    Route::Post('transactions/accept-transaction', [TransactionController::class,'AcceptTransaction']);



    #########################################################
    #                       others routs                    #
    #########################################################
    Route::resource('/property-types', PropertyTypeController::class);
    Route::resource('/operation-types', OperationTypeController::class);
    Route::resource('/route-types', RouteTypeController::class);

    Route::get('/cancellation-reasons', [CancellationReasonController::class,'index']);
    Route::get('/transaction-status', [TransactionStatusController::class,'index']);
    Route::get('/transaction-sub-status', [TransactionStatusController::class,'indexSub']);

    #########################################################
    #                       banks routs                    #
    #########################################################

    Route::resource('/banks', BankController::class);


    Route::get('/get-data-for-payment', [TransactionController::class,'GetDataForPayment' ]);



});

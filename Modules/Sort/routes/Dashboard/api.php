<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Sort\App\Http\Controllers\Dashboard\TransactionController;

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




    Route::Post('transactions/accept-transaction', [TransactionController::class,'AcceptTransaction']);
    Route::Post('transactions/set-value-transaction', [TransactionController::class,'AuthorityPaymentReceipt']);
    Route::Post('transactions/payment-confirmation', [TransactionController::class,'PaymentConfirmation']);
    Route::resource('/transactions', TransactionController::class);

});

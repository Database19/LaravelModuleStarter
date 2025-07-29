<?php

use Illuminate\Support\Facades\Route;
use Modules\PointOfSales\Http\Controllers\PointOfSalesController;
use Modules\PointOfSales\Http\Controllers\TransactionController;
use Modules\PointOfSales\Http\Controllers\PaymentController;
use Modules\PointOfSales\Http\Controllers\ReturnController;
use Modules\PointOfSales\Http\Controllers\ReceiptController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth', 'permission:manage-pos|manage-companies|super-admin-access'])->prefix('pointofsales')->name('pointofsales.')->group(function () {
    // Main POS Controller
    Route::resource('pointofsales', PointOfSalesController::class)->names('pointofsales');

    // Transactions
    Route::resource('transactions', TransactionController::class)->names('transactions');
    Route::post('transactions/{transaction}/complete', [TransactionController::class, 'complete'])->name('transactions.complete');
    Route::post('transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');

    // Payments
    Route::resource('payments', PaymentController::class)->names('payments');
    Route::post('payments/{payment}/process', [PaymentController::class, 'process'])->name('payments.process');

    // Returns
    Route::resource('returns', ReturnController::class)->names('returns');
    Route::post('returns/{return}/approve', [ReturnController::class, 'approve'])->name('returns.approve');

    // Receipts
    Route::resource('receipts', ReceiptController::class)->names('receipts');
    Route::get('receipts/{receipt}/print', [ReceiptController::class, 'print'])->name('receipts.print');
    Route::get('receipts/{receipt}/download', [ReceiptController::class, 'download'])->name('receipts.download');
});

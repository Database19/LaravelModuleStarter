<?php

use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\SalesOrderController;

Route::middleware(['auth', 'permission:manage-sales|manage-companies|super-admin-access'])
    ->prefix('sales')
    ->name('sales.')
    ->group(function () {

    // Rute untuk CRUD dasar
    Route::get('orders', [SalesOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [SalesOrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [SalesOrderController::class, 'store'])->name('orders.store');

    // Rute yang menggunakan Route-Model Binding.
    // Pastikan nama parameter {salesOrder} sama dengan variabel $salesOrder di controller.
    Route::get('orders/{salesOrder}', [SalesOrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{salesOrder}/edit', [SalesOrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{salesOrder}', [SalesOrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{salesOrder}', [SalesOrderController::class, 'destroy'])->name('orders.destroy');

    // Rute untuk Aksi Alur Kerja (Workflow)
    Route::post('orders/{salesOrder}/confirm', [SalesOrderController::class, 'confirm'])->name('orders.confirm');
    Route::post('orders/{salesOrder}/ship', [SalesOrderController::class, 'createShipment'])->name('orders.ship');
    Route::post('orders/{salesOrder}/invoice', [SalesOrderController::class, 'createInvoice'])->name('orders.invoice');
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Http\Controllers\AccountingController;
use Modules\Accounting\Http\Controllers\CoaController;
use Modules\Accounting\Http\Controllers\JournalController;
use Modules\Accounting\Http\Controllers\ReportController;

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

Route::prefix('accounting')->middleware(['auth', 'role:Admin|Accountant'])->group(function () {
    // Route::resource('/', AccountingController::class);
    Route::get('settings', [AccountingController::class, 'index'])->name('accounting.settings.index');
    Route::post('settings', [AccountingController::class, 'store'])->name('accounting.settings.store');

    Route::resource('coas', CoaController::class);
    Route::resource('journals', JournalController::class);

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/neraca-saldo', [ReportController::class, 'neracaSaldo'])->name('neraca_saldo');
        Route::get('/laba-rugi', [ReportController::class, 'labaRugi'])->name('laba_rugi');
        Route::get('/perubahan-modal', [ReportController::class, 'perubahanModal'])->name('perubahan_modal');
        Route::get('/neraca', [ReportController::class, 'neraca'])->name('neraca');
        Route::get('/buku-besar', [ReportController::class, 'bukuBesar'])->name('buku_besar');
        Route::get('/perubahan-modal', [ReportController::class, 'perubahanModal'])->name('perubahan_modal');
        Route::get('/mutasi-saldo', [ReportController::class, 'mutasiSaldo'])->name('mutasi_saldo');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Http\Controllers\AccountingController;
use Modules\Accounting\Http\Controllers\AccountingSettingsController;
use Modules\Accounting\Http\Controllers\CoaController;
use Modules\Accounting\Http\Controllers\JournalController;
use Modules\Accounting\Http\Controllers\ReportController;
use Modules\Accounting\Http\Controllers\FixedAssetController;
use Modules\Accounting\Http\Controllers\BudgetController;

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

Route::prefix('accounting')->name('accounting.')->group(function () {
    // Test route without authentication
    Route::get('settings-test', [AccountingSettingsController::class, 'index'])->name('settings.test');
    Route::get('settings-test/accounts-for-business', [AccountingSettingsController::class, 'getAccountsForBusinessType'])->name('settings.test.accounts-for-business');
    Route::get('settings-test/business-types', [AccountingSettingsController::class, 'getBusinessTypes'])->name('settings.test.business-types');
    Route::get('settings-test/account-recommendations', [AccountingSettingsController::class, 'getAccountRecommendations'])->name('settings.test.account-recommendations');
});

Route::prefix('accounting')->name('accounting.')->middleware(['auth', 'permission:manage-accounting|manage-companies|super-admin-access'])->group(function () {
    // Chart of Accounts
    Route::resource('coa', CoaController::class);

    // Journal Entries
    Route::resource('journals', JournalController::class);

    // Fixed Assets
    Route::resource('fixed-assets', FixedAssetController::class);

    // Budget Planning
    Route::resource('budget', BudgetController::class);
    Route::get('budget/{id}/transactions', [BudgetController::class, 'transactions'])->name('budget.transactions');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AccountingSettingsController::class, 'index'])->name('index');
        Route::post('/', [AccountingSettingsController::class, 'store'])->name('store');
        Route::post('/update-business-type', [AccountingSettingsController::class, 'updateBusinessType'])->name('update-business-type');
        Route::post('/reset-business-type', [AccountingSettingsController::class, 'resetBusinessType'])->name('reset-business-type');
        Route::get('/accounts-for-business', [AccountingSettingsController::class, 'getAccountsForBusinessType'])->name('accounts-for-business');
        Route::get('/business-types', [AccountingSettingsController::class, 'getBusinessTypes'])->name('business-types');
        Route::get('/account-recommendations', [AccountingSettingsController::class, 'getAccountRecommendations'])->name('account-recommendations');
    });

    // Financial Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/neraca-saldo', [ReportController::class, 'neracaSaldo'])->name('neraca_saldo');
        Route::get('/laba-rugi', [ReportController::class, 'labaRugi'])->name('laba_rugi');
        Route::get('/perubahan-modal', [ReportController::class, 'perubahanModal'])->name('perubahan_modal');
        Route::get('/neraca', [ReportController::class, 'neraca'])->name('neraca');
        Route::get('/buku-besar', [ReportController::class, 'bukuBesar'])->name('buku_besar');
        Route::get('/mutasi-saldo', [ReportController::class, 'mutasiSaldo'])->name('mutasi_saldo');
    });

    // API Routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/accounts', [CoaController::class, 'getAccountsApi'])->name('accounts');
    });
});

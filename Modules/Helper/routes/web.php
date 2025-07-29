<?php

use Illuminate\Support\Facades\Route;
use Modules\Helper\Http\Controllers\HelperController;
use Modules\Helper\Http\Controllers\CrudHelperController;

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

Route::group(['prefix' => 'helper', 'as' => 'helper.', 'middleware' => ['auth', 'permission:manage-helper']], function () {
    Route::get('/', [HelperController::class, 'index'])->name('index');

    // CRUD Helper Routes
    Route::group(['prefix' => 'crud-helper', 'as' => 'crud-helper.', 'middleware' => ['auth', 'permission:manage-helper']], function () {
        Route::get('/', [CrudHelperController::class, 'index'])->name('index');
        Route::post('/generate', [CrudHelperController::class, 'generate'])->name('generate');
        Route::post('/preview', [CrudHelperController::class, 'preview'])->name('preview');
    });
});

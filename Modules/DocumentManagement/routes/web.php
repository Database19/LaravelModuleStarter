<?php

use Illuminate\Support\Facades\Route;
use Modules\DocumentManagement\Http\Controllers\DocumentManagementController;
use Modules\DocumentManagement\Http\Controllers\LibraryController;
use Modules\DocumentManagement\Http\Controllers\CategoryController;
use Modules\DocumentManagement\Http\Controllers\TemplateController;

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

Route::middleware(['auth', 'permission:manage-documents|manage-companies|super-admin-access'])->prefix('documents')->name('documents.')->group(function () {
    Route::resource('documentmanagement', DocumentManagementController::class)->names('documentmanagement');

    // Document Library
    Route::resource('library', LibraryController::class);

    // Document Categories
    Route::resource('categories', CategoryController::class);

    // Document Templates
    Route::resource('templates', TemplateController::class);
});

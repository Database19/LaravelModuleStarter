<?php

use Illuminate\Support\Facades\Route;
use Modules\Helpdesk\Http\Controllers\HelpdeskController;
use Modules\Helpdesk\Http\Controllers\TicketController;
use Modules\Helpdesk\Http\Controllers\KnowledgeController;
use Modules\Helpdesk\Http\Controllers\CategoryController;

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
Route::middleware(['auth', 'permission:manage-helpdesk|manage-companies|super-admin-access'])->prefix('helpdesk')->name('helpdesk.')->group(function () {
    Route::resource('helpdesk', HelpdeskController::class)->names('helpdesk');

    // Tickets
    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/comment', [TicketController::class, 'addComment'])->name('tickets.comment');
    Route::post('tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');

    // Knowledge Base
    Route::resource('knowledge', KnowledgeController::class);

    // Categories
    Route::resource('categories', CategoryController::class);
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\ProjectManagement\Http\Controllers\ProjectManagementController;


Route::middleware(['auth', 'role:Admin|Project Manager'])->prefix('project')->name('project.')->group(function () {
    Route::resource('management', ProjectManagementController::class);
});

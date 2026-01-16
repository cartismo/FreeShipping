<?php

use Illuminate\Support\Facades\Route;
use Modules\FreeShipping\Http\Controllers\Admin\FreeShippingController;

/*
|--------------------------------------------------------------------------
| FreeShipping Admin Routes
|--------------------------------------------------------------------------
|
| Admin routes for free shipping settings.
|
*/

Route::prefix('modules/shipping/free-shipping')->name('admin.shipping.free.')->group(function () {
    Route::get('/settings', [FreeShippingController::class, 'index'])->name('settings');
    Route::put('/settings', [FreeShippingController::class, 'update'])->name('settings.update');
});
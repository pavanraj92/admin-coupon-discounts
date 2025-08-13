<?php

use Illuminate\Support\Facades\Route;
use Admin\Coupons\Controllers\CouponManagerController;

Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {
    Route::resource('coupons', CouponManagerController::class);
    Route::post('coupons/updateStatus', [CouponManagerController::class, 'updateStatus'])->name('coupons.updateStatus');
});

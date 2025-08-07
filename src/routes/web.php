<?php

use Illuminate\Support\Facades\Route;
use Admin\Coupons\Controllers\CouponManagerController;

Route::name('admin.')->middleware(['web','auth:admin'])->group(function () {
    Route::resource('coupons', CouponManagerController::class);
    Route::post('coupons/updateStatus', [CouponManagerController::class, 'updateStatus'])->name('coupons.updateStatus');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\MikrotikController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['web'])->group(function () {
    Route::get('/login', [MikrotikController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [MikrotikController::class, 'login'])->name('login.submit');
    Route::get('/logout', [MikrotikController::class, 'logout'])->name('logout');
    Route::get('/voucher-list', [MikrotikController::class, 'voucherList'])->name('voucher.list');
    Route::get('/create-voucher', [MikrotikController::class, 'createVoucher'])->name('create.voucher');
    Route::post('/sell-voucher', [MikrotikController::class, 'sellVoucher'])->name('sell.voucher');
});

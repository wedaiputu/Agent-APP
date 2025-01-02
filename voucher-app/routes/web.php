<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AgentLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Mikrotik\MikrotikController;
use Illuminate\Support\Facades\Auth;


Route::middleware(['web'])->group(function () {
    Route::get('mikrotiklogin', [MikrotikController::class, 'index'])->name('mikrotik.mikrotiklogin');

    Route::post('mikrotiklogin', [MikrotikController::class, 'mikrotikLogin'])->name('mikrotik.mikrotiklogin');
});
Route::prefix('agent')->group(function () {
    // Login Routes
    Route::get('login', [AgentLoginController::class, 'showLoginForm'])->name('agent.login');
    Route::post('login', [AgentLoginController::class, 'login'])->name('agent.login.submit');
    
    // Add logout route
    Route::post('logout', [AgentLoginController::class, 'logout'])->name('agent.logout');

    // Protected routes
    Route::middleware(['auth:agent'])->get('/dashboard', [DashboardController::class, 'index'])->name('agent.dashboard');
    Route::get('/voucher-list', [MikrotikController::class, 'voucherList'])->name('voucher.list');    
Route::post('/update-voucher', [MikrotikController::class, 'updateVoucherComment'])->name('voucher.update');
});

// Route::get('/logout', [MikrotikController::class, 'logout'])->name('logout');
// Route::get('/create-voucher', [MikrotikController::class, 'createVoucher'])->name('create.voucher');
// Route::get('/vouchers', [MikrotikController::class, 'voucherList']);  // View all vouchers
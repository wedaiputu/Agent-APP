<?php

use App\Http\Controllers\PostController;

use App\Http\Controllers\VoucherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function (){
    return "API";
});

Route::apiResource('posts', PostController::class);
Route::apiResource('vouchers', VoucherController::class);
// Route::post('/logout', [AdminPageController::class, 'logout'])->name('logout');

Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');



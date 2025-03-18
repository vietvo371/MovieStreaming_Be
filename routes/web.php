<?php

use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\LoginGoogleController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\MomoController;
use App\Http\Controllers\VNPayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/anime',[TestController::class, 'test']);

// Route::controller(LoginGoogleController::class)->group(function () {
//     Route::get('auth/google', 'getGoogleSignInUrl');
//     Route::get('auth/google/callback', 'loginCallback');
// });


Route::get('payment-status', [VNPayController::class, 'showPaymentResult'])->name('vnpay.payment.callback');



Route::get('kich-hoat-email/{hash}', [KhachHangController::class, 'kichHoatEmail'])->name('kich-hoat-email');
Route::get('quen-mat-khau/{hash}', [KhachHangController::class, 'quenMatKhau'])->name('quen-mat-khau');




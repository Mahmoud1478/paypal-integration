<?php

use App\Http\Controllers\PaypalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PaypalController::class, 'checkout'])->name('checkout-view');
Route::post('/send-paypal', [PaypalController::class, 'sendToPaypal'])->name('checkout');
Route::get('/success', [PaypalController::class, 'success'])->name('success');
Route::get('/cancel', [PaypalController::class, 'cancel'])->name('cancel');



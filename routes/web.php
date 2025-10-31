<?php

use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])
    ->name('front.index');

Route::get('/go-to-store', [FrontController::class, 'redirectToStore'])
    ->name('front.go-to-store');

Route::get('/transactions', [FrontController::class, 'transactions'])
    ->name('front.transactions');

Route::post('/transactions/details', [FrontController::class, 'transaction_details'])
    ->name('front.transaction_details');

Route::get('/search', [FrontController::class, 'search'])
    ->name('front.search');

Route::get('/store/details/{Bengkel:slug}', [FrontController::class, 'details'])
    ->name('front.details');

Route::post('/booking/payment/submit', [FrontController::class, 'booking_payment_store'])
    ->name('front.booking.payment.store');

Route::get('/booking/{Bengkel:slug}', [FrontController::class, 'booking'])
    ->name('front.booking');

Route::post('/booking/{Bengkel:slug}', [FrontController::class, 'booking_store'])
    ->name('front.booking.store');

Route::get('/booking/{Bengkel}/{ServisMobil}/payment', [FrontController::class, 'booking_payment'])
    ->name('front.booking.payment');

Route::get('/booking/success/{KelolaPemesanan}', [FrontController::class, 'success_booking'])
    ->name('front.success.booking');
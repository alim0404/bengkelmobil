<?php

use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Artisan;
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

// routes/web.php

// Rating routes
Route::get('/rating/{trx_id}', [FrontController::class, 'rating'])
    ->name('front.rating');

Route::post('/rating', [FrontController::class, 'rating_store'])
    ->name('front.rating.store');

Route::get('/rating/{trx_id}/edit', [FrontController::class, 'rating_edit'])
    ->name('front.rating.edit');

Route::put('/rating/{trx_id}/update', [FrontController::class, 'rating_update'])
    ->name('front.rating.update');

Route::get('/invoice/{trx_id}', [FrontController::class, 'invoice'])
    ->name('front.invoice');


Route::get('/storage-link', function () {
    try {
        Artisan::call('storage:link');
        return "Storage link berhasil dibuat!";
    } catch (\Exception $e) {
        return "Gagal membuat storage link: " . $e->getMessage();
    }
});
<?php

use App\Http\Controllers\MemberPortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/portal', [MemberPortalController::class, 'index'])
    ->name('portal.lookup');

Route::get('/status', [MemberPortalController::class, 'index'])
    ->name('portal.status');

Route::post('/portal/lookup', [MemberPortalController::class, 'lookup'])
    ->name('portal.find');

Route::get('/pass/{qr_code}', [MemberPortalController::class, 'showPass'])
    ->name('portal.pass');

Route::post('/pass/{qr_code}/pay', [MemberPortalController::class, 'pay'])
    ->name('portal.pay');

Route::view('/payments/success', 'payments.result', ['status' => 'success'])
    ->name('payments.success');

Route::view('/payments/cancelled', 'payments.result', ['status' => 'cancelled'])
    ->name('payments.cancelled');

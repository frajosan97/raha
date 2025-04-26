<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\MpesaController;

/**
 * ---------------------------------------------------
 * M-Pesa Callback Endpoints
 * ---------------------------------------------------
 * These endpoints are called by Safaricom's M-Pesa API
 * to report results of transactions and validations.
 */
Route::prefix('/payments')->group(function () {
    Route::post('/callback', [MpesaController::class, 'callback'])->name('payments.callback');
    Route::post('/confirmation', [MpesaController::class, 'confirmation'])->name('payments.confirmation');
    Route::post('/validation', [MpesaController::class, 'validation'])->name('payments.validation');
    Route::post('/timeout', [MpesaController::class, 'timeout'])->name('payments.timeout');
    Route::post('/result', [MpesaController::class, 'result'])->name('payments.result');
});

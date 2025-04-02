<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});
Route::get('/get-escorts', [ApiController::class, 'getEscorts'])->name('get-escorts');
Route::get('/singles-near-me', [ApiController::class, 'singlesNearMe'])->name('singles-near-me');

Auth::routes(['verify' => true]);

Route::prefix('mpesa')->group(function () {
    Route::post('/registerUrl', [MpesaController::class, 'registerUrl'])->name('mpesa.registerUrl');
    Route::post('/deposit', [MpesaController::class, 'stkPush'])->name('mpesa.stkPush');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/escort/{id}', [UserController::class, 'show'])->name('escort_view');
});

/**
 * ==========================================================================================
 * PORTALS ROUTES
 * ==========================================================================================
 */
Route::group(['prefix' => 'portal', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/updateUserLocation', [DashboardController::class, 'updateUserLocation'])->name('updateUserLocation');

    // Resource Controllers for Games, Categories, and Users
    Route::resources([
        'profile' => UserController::class,
    ]);

    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::get('/subscription-pay/{id}', [SubscriptionController::class, 'pay'])->name('subscription.pay');
});

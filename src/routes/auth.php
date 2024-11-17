<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\MailController;

Route::middleware('guest:user')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth:web')->prefix('email')->controller(MailController::class)->group(function () {
    Route::get('/verify', 'unverified')->name('verification.notice');
    Route::get('/verify/{id}/{hash}', 'verify_complete')->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/verification-notification', 'retransmission')->middleware('throttle:6,1')->name('verification.send');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RegisteredUserController;
use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WriterController;

Route::middleware('guest:admin')->controller(RegisteredUserController::class)->group(function () {
    Route::get('register', [RegisteredUserController::class,'create'])->name('register');
    Route::post('register', [RegisteredUserController::class,'store']);
    Route::get('login', [AuthenticatedSessionController::class,'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class,'store']);
});

Route::middleware('auth:admin')->prefix('email')->controller(MailController::class)->group(function () {
    Route::get('/verify', 'unverified')->name('verification.notice');
    Route::get('/verify/{id}/{hash}', 'verify_complete')->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/verification-notification', 'retransmission')->middleware('throttle:6,1')->name('verification.send');
});

Route::middleware(['auth:admin', 'role:admin'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/user/index', 'userShow')->name('admin.user.index');
        Route::get('/add', 'store')->name('admin.add');
        Route::get('/delete', 'destroy')->name('admin.delete');
    });
});

Route::middleware(['auth', 'role:admin|writer'])->prefix('writer')->controller(WriterController::class)->group(function () {
    Route::get('/shop-edit', 'editShow')->name('shop-edit');
    Route::post('/shop-edit', 'create_and_edit')->name('shop-edit.create');
    Route::get('/confirm/shop-reservation', 'reservationShow')->name('confirm-shop-reservation');
    Route::patch('/update/shop-reservation', 'update')->name('update-shop-reservation');
    Route::delete('/destroy/shop-reservation', 'destroy')->name('destroy-shop-reservation');
});


Route::middleware('auth:admin')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
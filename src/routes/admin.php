<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RegisteredUserController;
use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WriterController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;

Route::get('register', [RegisteredUserController::class,'create'])->name('admin.register');
Route::post('register', [RegisteredUserController::class,'store']);
Route::get('login', [AuthenticatedSessionController::class,'create'])->name('admin.login');
Route::post('login', [AuthenticatedSessionController::class,'store']);

Route::middleware('auth:admin')->prefix('email')->controller(MailController::class)->group(function () {
    Route::get('/verify', 'unverified')->name('admin.verification.notice');
    Route::get('/verify/{id}/{hash}', 'verify_complete')->middleware(['signed', 'throttle:6,1'])->name('admin.verification.verify');
    Route::post('/verification-notification', 'retransmission')->middleware('throttle:6,1')->name('admin.verification.send');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('/user/index', [AdminController::class, 'userShow'])->name('admin.user.index');
    Route::post('/add', [AdminController::class, 'store'])->name('admin.add');
    Route::post('/delete', [AdminController::class, 'destroy'])->name('admin.delete');
    Route::get('/email_notification', [AdminController::class, 'email_notification'])->name('admin.notification');
    Route::post('/email_notification', [AdminController::class, 'sendNotification'])->name('admin.send_notification');

});

Route::middleware('auth:admin')->prefix('writer')->group(function () {
    Route::get('/shop-add', [WriterController::class, 'addShow'])->name('shop-add');
    Route::post('/shop-add', [WriterController::class, 'create'])->name('shop-add.create');
    Route::get('/shop-edit', [WriterController::class, 'editShow'])->name('shop-edit');
    Route::post('/shop-edit', [WriterController::class, 'create_and_edit'])->name('shop-edit.create');
    Route::get('/confirm/shop-reservation', [WriterController::class, 'reservationShow'])->name('confirm-shop-reservation');
    Route::patch('/update/shop-reservation', [WriterController::class, 'update'])->name('update-shop-reservation');
    Route::delete('/destroy/shop-reservation', [WriterController::class, 'destroy'])->name('destroy-shop-reservation');

});

Route::get('/admin/done', function () {return view('admin.done');});
Route::get('/verify/reservation/{reservation_id}', [ReservationController::class, 'verifyQrCode'])->name('reservation.verify');
Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');

Route::middleware('auth:admin')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
});
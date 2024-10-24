<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [ShopController::class, 'index'])->name('index');
Route::get('/detail/{shop_id}', [ShopController::class, 'detail'])->name('detail');
Route::get('/search', [ShopController::class, 'search']);
Route::get('/thanks', function () {return view('thanks');});
Route::get('/done', function () {return view('done');});

Route::post('/register', [ShopController::class, 'register'])->name('register');
Route::get('/email/verify', [MailController::class, 'unverified'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [MailController::class, 'verify_complete'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [MailController::class, 'retransmission'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('/reservation', [ReservationController::class, 'reservation'])->middleware('verified');
Route::get('/reservation/edit/{id}', [ReservationController::class,'edit'])->name('reservation.edit');
Route::post('/reservation/update/{id}', [ReservationController::class, 'update'])->middleware('verified')->name('reservation.update');
Route::post('/reservation/delete', [ReservationController::class, 'destroy']);

Route::get('/my_page', [MyPageController::class, 'my_page'])->name('my_page')->middleware('verified');
Route::post('/favorite', [FavoriteController::class,'favorite'])->middleware('verified');
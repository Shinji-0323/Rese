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

Route::controller(ShopController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/detail/{shop_id}', 'detail')->name('detail');
    Route::get('/search', 'search');
    Route::post('/register', 'register')->name('register');
});

Route::get('/thanks', function () {return view('thanks');});
Route::get('/done', function () {return view('done');});

Route::middleware('auth')->prefix('email')->group(function () {
    Route::controller(MailController::class)->group(function () {
        Route::get('/verify', 'unverified')->name('verification.notice');
        Route::get('/verify/{id}/{hash}', 'verify_complete')->middleware('signed')->name('verification.verify');
        Route::post('/verification-notification', 'retransmission')->middleware('throttle:6,1')->name('verification.send');
    });
});

Route::prefix('reservation')->controller(ReservationController::class)->group(function () {
    Route::post('/', 'reservation')->middleware('verified');
    Route::get('/edit/{id}', 'edit')->name('reservation.edit');
    Route::post('/update/{id}', 'update')->middleware('verified')->name('reservation.update');
    Route::post('/delete', 'destroy');
});

Route::get('/my_page', [MyPageController::class, 'my_page'])->name('my_page')->middleware('verified');
Route::post('/favorite', [FavoriteController::class,'favorite'])->middleware('verified');

Route::prefix('review')->controller(ReviewController::class)->group(function () {
    Route::get('/{shop_id}', 'index')->name('review');
    Route::post('/store/{shop_id}', 'store')->name('review.store');
    Route::post('/delete/{review_id}', 'delete');
});
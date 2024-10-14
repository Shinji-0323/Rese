<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ReviewController;

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

Route::get('/email/verify', [MailController::class, 'unverified'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [MailController::class, 'verify_complete'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [MailController::class, 'retransmission'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/', [ShopController::class, 'index'])->name('index');
Route::get('/detail/{id}', [ShopController::class, 'detail'])->name('detail');
Route::post('/reservation', [StoreInformationController::class, 'reservation'])->middleware('verified');
Route::get('/done', function(){return view('done');});
Route::post('/update', [StoreInformationController::class, 'update'])->middleware('verified');
Route::get('/search', [StoreInformationController::class, 'search'])->middleware('verified');

Route::get('/my_page', [UserInformationController::class, 'my_page'])->name('my_page')->middleware('verified');
Route::get('/delete_reservation', [UserInformationController::class, 'delete_reservation']);
Route::get('/reservation_change', [UserInformationController::class,'change']);
Route::get('/favorite', [FavoriteController::class,'favorite'])->middleware('verified');
Route::get('/evaluation', [UserInformationController::class,'evaluation']);
Route::get('/verified', function () {
    return view('verified');
});
Route::get('/thanks', function () {
    return view('thanks');
});
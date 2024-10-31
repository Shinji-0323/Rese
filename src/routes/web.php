<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\FavoriteController;
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

Route::controller(ShopController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/detail/{shop_id}', 'detail')->name('detail');
    Route::get('/search', 'search');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
});

Route::get('/thanks', function () {return view('auth.thanks');});
Route::get('/done', function () {return view('done');});

Route::prefix('reservation')->controller(ReservationController::class)->group(function () {
    Route::post('/', 'reservation')->middleware('verified');
    Route::get('/edit/{id}', 'edit')->name('reservation.edit');
    Route::post('/update/{id}', 'update')->middleware('verified')->name('reservation.update');
    Route::post('/delete', 'destroy');
});

Route::middleware(['auth', 'verified'])->group(function (){
    Route::get('/my_page', [MyPageController::class, 'my_page'])->name('my_page');
    Route::post('/favorite', [FavoriteController::class,'favorite']);
});

Route::prefix('review')->controller(ReviewController::class)->group(function () {
    Route::get('/{shop_id}', 'index')->name('review');
    Route::post('/store/{shop_id}', 'store')->name('review.store');
    Route::post('/delete/{review_id}', 'delete');
    Route::get('/shop/{shop_id}', 'list');
});

require __DIR__.'/auth.php';



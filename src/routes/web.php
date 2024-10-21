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


Route::get('/', [ShopController::class, 'index'])->name('index');
Route::get('/detail/{shop_id}', [ShopController::class, 'detail'])->name('detail');
Route::get('/search', [ShopController::class, 'search']);
Route::get('/thanks', function () {return view('thanks');});
Route::get('/done', function () {return view('done');});

Route::post('/reservation', [ReservationController::class, 'reservation'])->middleware('verified');
Route::get('/reservation/edit', [ReservationController::class,'edit']);
Route::post('/reservation/update', [ReservationController::class, 'update'])->middleware('verified');
Route::post('/reservation/delete', [ReservationController::class, 'destroy']);

Route::get('/my_page', [MyPageController::class, 'my_page'])->name('my_page')->middleware('verified');
Route::post('/favorite', [FavoriteController::class,'favorite'])->middleware('verified');
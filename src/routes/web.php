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
Route::post('/reservation', [ReservationController::class, 'reservation'])->middleware('verified');
Route::get('/reservation/edit', [ReservationController::class,'edit']);
Route::post('/reservation/update', [ReservationController::class, 'update'])->middleware('verified');
Route::post('/reservation/delete', [ReservationController::class, 'destroy']);
Route::get('/my_page', [MyPageController::class, 'my_page'])->name('my_page')->middleware('verified');
Route::get('/favorite', [FavoriteController::class,'favorite'])->middleware('verified');

/* Route::get('/feedback/{reservation_id}', [FeedbackController::class, 'create'])
Route::post('/feedback/store', [FeedbackController::class, 'store'])

Route::get('/review/add/{shop_id}', [ReviewController::class, 'create'])
Route::post('/review/store', [ReviewController::class, 'store'])
Route::post('/review/delete', [ReviewController::class, 'destroy'])
Route::get('/review/edit/{shop_id}', [ReviewController::class, 'edit'])
Route::post('/review/update', [ReviewController::class, 'update'])
 */
Route::get('/search', [Controller::class, 'search'])->middleware('verified');

Route::get('/thanks', function () {return view('thanks');});
Route::get('/done', function () {return view('done');});
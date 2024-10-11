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

Route::get('/', [ShopController::class, 'index']);
Route::get('/detail/{shop_id}', [ShopController::class, 'detail']);
Route::view('/thanks', 'auth.thanks');
Route::get('/done', function () {return view('reservation.done');});

Route::middleware('verified')->group(function () {
    Route::post('/reserve', [ReservationController::class, 'store']);
    Route::get('/reserve/edit', [ReservationController::class, 'edit']);
    Route::post('/reserve/update', [ReservationController::class, 'update']);
    Route::get('/reserve/delete', [ReservationController::class, 'destroy']);
    Route::get('/my_page', [MyPageController::class, 'my_page']);
    Route::post('/favorite', [FavoriteController::class, 'flip']);
    Route::get('/feedback/{reservation_id}', [FeedbackController::class, 'create']);
    Route::post('/feedback/store', [FeedbackController::class, 'store']);
    Route::get('/review/add/{shop_id}', [ReviewController::class, 'create']);
    Route::post('/review/store', [ReviewController::class, 'store']);
    Route::post('/review/delete', [ReviewController::class, 'destroy']);
    Route::get('/review/edit/{shop_id}', [ReviewController::class, 'edit']);
    Route::post('/review/update', [ReviewController::class, 'update']);
});

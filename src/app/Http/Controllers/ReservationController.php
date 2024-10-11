<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Favorite;
use App\Models\Review;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        Reservation::create([
            'user_id' => Auth::id(),
            'shop_id' => $request->input('shop_id'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'number' => $request->input('number'),
        ]);

        return redirect('/done');
    }

    /* public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return back();
    }

    public function edit(Reservation $reservation)
    {
        $user = Auth::user();
        $shop = Shop::find($reservation->shop_id);
        $review = Review::where('user_id', $user->id)->where('shop_id', $shop->id)->first();

        $shopReviews = Review::where('shop_id', $reservation->shop_id)->get();
        $avgRating = round(Review::where('shop_id', $reservation->shop_id)->avg('rating'), 1);
        $countFavorites = Favorite::where('shop_id', $reservation->shop_id)->count();

        $backRoute = '/mypage';

        return view('detail', compact('reservation', 'user', 'shop', 'review', 'shopReviews', 'avgRating', 'countFavorites', 'backRoute'));
    }

    public function update(ReservationRequest $request, Reservation $reservation)
    {
        $edit = $request->all();
        Reservation::find($reservation->id)->update($edit);
        return redirect('/done');
    } */
}

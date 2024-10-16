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
    public function reservation(ReservationRequest $request)
    {
        $form = $request->all();
        unset($form['_token']);
        Reservation::create($form);
        return redirect('/done');
    }

    public function edit(Request $request)
    {
        $reservation_id = $request->reservation_id;
        Reservation::find($reservation_id)->delete();

        return redirect('/my_page');
    }

    public function destroy(Request $request)
    {
        $reservation_id = $request->reservation_id;
        Reservation::find($reservation_id)->delete();
        return redirect('/my_page');
    }

    public function update(Request $request)
    {
        $reservation = $request->all();
        $shop = Shop::where('id',$reservation['shop_id'])->with('area','genre')->first();
        $reservation = Reservation::where('user_id',$reservation['user_id'])->where('shop_id',$reservation['shop_id'])->first();
        return view('shop_detail',compact('shop','reservation'));
    }
}

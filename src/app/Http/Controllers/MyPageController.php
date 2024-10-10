<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Favorite;
use App\Models\Reservation;
use DateTime;

class MyPageController extends Controller
{
    public function my_page()
    {
        $user = Auth::user();
        $today = new DateTime();
        $today = $today->format('Y-m-d');
        $reservations = Reservation::where('user_id', $user->id)->where('date','>=',$today)->with('shop','user')->get();
        $favorites = Favorite::where('user_id', $user->id)->with('shop')->get();
        foreach($favorites as $favorite){
            $region = Shop::find($favorite->id);
            $genre = Shop::find($favorite->id);
            $favorite->region = $region["name"];
            $favorite->genre = $genre["name"];
        };

        return view('my_page', compact(
            'reservations', 'favorites'));
    }

    public function delete_reservation(Request $request)
    {
        $reservation_id = $request->reservation_id;
        Reservation::find($reservation_id)->delete();

        return redirect('/my_page');
    }

    public function change(Request $request)
    {
        $reservation = $request->all();
        $shop = Shop::where('id',$reservation['shop_id'])->with('area','genre')->first();
        $reservation = Reservation::where('user_id',$reservation['user_id'])->where('shop_id',$reservation['shop_id'])->first();
        return view('shop_detail',compact('shop','reservation'));
    }

}

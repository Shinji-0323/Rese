<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Favorite;
use App\Models\Reservation;
use Carbon\Carbon;

class MyPageController extends Controller
{
    public function my_page()
    {
        $id = Auth::id();
        $now = Carbon::now();
        $reservations = Reservation::where('user_id',$id)->where(function ($query) use ($now) {
            $query->where('date', '>', $now->toDateString())
            ->orWhere(function ($query) use ($now) {
                $query->where('date', '=', $now->toDateString())
                ->where('time', '>', $now->toTimeString());
                });
            })->with('shop', 'user')->get();
        $histories = Reservation::where('user_id',$id)->where(function ($query) use ($now) {
            $query->where('date', '<', $now->toDateString())
            ->orWhere(function ($query) use ($now) {
                $query->where('date', '=', $now->toDateString())
                ->where('time', '<=', $now->toTimeString());
                });
            })->with('shop', 'user')->get();
        $favorites = Favorite::where('user_id',$id)->with('shop')->get();
        foreach($favorites as $favorite){
            $region = Shop::find($favorite->shop['region']);
            $genre = Shop::find($favorite->shop['genre']);
            $favorite->region = $region;
            $favorite->genre = $genre;
        };
        return view('my_page',compact('reservations', 'histories', 'favorites'));
    }
}

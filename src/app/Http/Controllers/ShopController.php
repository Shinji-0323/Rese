<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Favorite;
use App\Models\Admin;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $all_area = 'All area';
        $all_genre = 'All genre';

        $shops = Shop::all();
        $regions = array($all_area);
        $genres = array($all_genre);
        foreach ($shops as $shop) {
            $regions[] = $shop['region'];
            $genres[] = $shop['genre'];
        }
        $regions = array_unique($regions);
        $genres = array_unique($genres);

        $favorites = array();
        /*if ( Auth::check() ) {
            $tmp_favorites = Favorite::select()->UserSearch(Auth::id())->get();
            foreach ($tmp_favorites as $tmp_favorite) {
                $favorites[] = $tmp_favorite['shop_id'];
            }
        }*/

        return view('index', compact('shops', 'regions', 'genres', 'favorites'));
    }

    public function detail(Request $request)
    {
        /* $user = Auth::user();
        $userId = Auth::id(); */
        $shop = Shop::find($request->shop_id);
        $from = $request->input('from');

        $countFavorites = Favorite::where('shop_id', $shop->id)->count();

        return view('detail', compact(/* 'user', */ 'shop', 'countFavorites'));
    }
}

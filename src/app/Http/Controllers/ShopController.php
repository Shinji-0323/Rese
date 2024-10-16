<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Favorite;
use App\Models\MyPage;
use App\Models\Admin;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use DateTime;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $all_area = 'All area';
        $all_genre = 'All genre';

        $shops = Shop::all();
        $id = Auth::id();
        $regions = array($all_area);
        $genres = array($all_genre);
        foreach ($shops as $shop) {
            $regions[] = $shop['region'];
            $genres[] = $shop['genre'];
        }
        $regions = array_unique($regions);
        $genres = array_unique($genres);
        $favorites = Favorite::where('user_id',$id)->get();

        return view('index', compact('shops', 'regions', 'genres','favorites'));
    }

    public function detail($id)
    {
        $shop = Shop::where('id',$id)->first();
        $today = new DateTime();
        $today_date = $today->format('Y-m-d');

        $backRoute = '/';

        return view('detail',compact('shop','today_date', 'backRoute'));
    }
}

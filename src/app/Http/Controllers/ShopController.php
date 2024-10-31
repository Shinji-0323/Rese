<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shop;
use App\Models\Favorite;
use App\Models\MyPage;
use App\Models\Admin;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    public function search(Request $request)
    {
        $element = $request->all();
        $query = Shop::query();
        $search = array_filter($element);
        if(!empty($search['region'])){
            $query->where('region',$search['region']);
        }
        if(!empty($search['genre'])){
            $query->where('genre',$search['genre']);
        }
        if(!empty($search['name'])){
            $query->where('name','like','%' . $search['name'] . '%');
        }
        $shops = $query->get();
        $id = Auth::id();
        $favorites = Favorite::where('user_id',$id)->get();
        return view('index', compact('shops','favorites'));
    }

    public function create()
    {
        return view('layouts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/shop_images');
            $imageName = basename($path);
        }

        $shop = new Shop();
        $shop->name = $request->input('name');
        $shop->region = $request->input('region');
        $shop->genre = $request->input('genre');
        $shop->description = $request->input('description');
        $shop->image_url = $imageName;
        $shop->save();

        return redirect()->route('index');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Shop;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function favorite(Request $request)
    {
        $shop_id = $request['shop_id'];
        $id = Auth::id();
        $favorite = Favorite::where('user_id',$id)->where('shop_id',$shop_id)->first();
        if(!$favorite){
            Favorite::create([
            'user_id' => $id,
            'shop_id' => $shop_id,
            ]);
        }else{
            Favorite::find($favorite->id)->delete();
        };

        return redirect('/');
    }
}

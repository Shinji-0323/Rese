<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Models\Favorite;
use App\Models\Review;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    public function index($shop_id)
    {
        $userId = Auth::id();

        $review = Review::where('user_id', $userId)->where('shop_id', $shop_id)->first();
        $shop = Shop::where('id', $shop_id)->first();
        if (!$shop) {
            dd('Shop not found for shop_id: ' . $shop_id);
        }
        $favorites = Auth::user()->favorites()->pluck('shop_id')->toArray();

        return view('reviews.index', compact('review', 'shop', 'favorites'));
    }

    public function store(ReviewRequest $request, $shop_id)
    {
        $userId = Auth::id();
        $review = Review::where('user_id', $userId)->where('shop_id', $shop_id)->first();

        $star = $request->input('star');  // 'star' というフィールドで送信されているか確認
        $comment = $request->input('comment');
        $imageUrl = null;

    // 画像がある場合、処理する
    if ($request->hasFile('image_url')) {
        $file = $request->file('image_url');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('reservationsystem-restaurant', $filename, 's3');
        $imageUrl = Storage::disk('s3')->url($path);
    }

    // 口コミが既に存在する場合、更新
    if ($review) {
        $review->update([
            'star' => $star,
            'comment' => $comment,
            'image_url' => $imageUrl,
        ]);
    } else {
        // 新しい口コミを作成
        Review::create([
            'user_id' => $userId,
            'shop_id' => $shop_id,
            'star' => $star,
            'comment' => $comment,
            'image_url' => $imageUrl,
        ]);
    }

        return view('reviews.thanks', compact('shop_id'));
    }

    public function delete($review_id)
    {
        Review::find($review_id)->delete();
        return redirect()->back()->with('success','口コミを削除しました');
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $shop = shop::find($request->shop_id);
        $shopReviews = Review::where('shop_id', $request->shop_id)->get();
        $avgStar = round(Review::where('shop_id', $request->shop_id)->avg('star'), 1);
        $countFavorites = Favorite::where('shop_id', $request->shop_id)->count();

        return view('reviews.list', compact('user','shop', 'shopReviews', 'avgStar'));
    }
}

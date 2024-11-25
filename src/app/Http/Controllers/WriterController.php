<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AddShopRequest;
use App\Http\Requests\UpdateShopRequest;

class WriterController extends Controller
{
    public function addShow()
    {
        $shops = Shop::all();

        return view('writer.shop_add', compact('shops'));
    }

    public function create(AddShopRequest $request)
    {

        $shopData = $request->except(['_token', 'image_url']);

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('public/shop_images');
            $shopData['image_url'] = Storage::url($path);
        }

        $createdShop = Shop::create($shopData);

        if (Auth::guard('admin')->user()->role === 'store_manager') {
            Auth::guard('admin')->user()->shops()->attach($createdShop->id);
        }

        return back()->with('success', '店舗情報を作成しました。');
    }

    public function editShow($shopId)
    {
        $shop = Auth::user()->shops()->where('shops.id', $shopId)->first(); // 編集する店舗を取得

        if (!$shop) {
            return redirect()->route('admin.user.index')->withErrors(['店舗が見つかりません。']);
        }

        $shops = Auth::user()->shops;

        return view('writer.shop_edit', compact('shop', 'shops'));
    }

    public function create_and_edit(UpdateShopRequest $request)
    {
        $shopId = $request->input('shop_id'); // フォームから店舗IDを取得
        $shop = Auth::guard('admin')->user()->shops()->where('shops.id', $shopId)->first();

        if (!$shop) {
            return redirect()->route('admin.user.index')->withErrors(['店舗が見つかりません。']);
        }

        $shopData = $request->except(['_token', 'image_url', 'shop_id']);

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('public/shop_images');
            $shopData['image_url'] = Storage::url($path);
        }

        $shop->update($shopData);

        return back()->with('success', '店舗情報を更新しました。');
    }

    public function reservationShow(Request $request)
    {
        $displayDate = Carbon::parse($request->input('displayDate'));

        if ($request->has('prevDate')) {
            $displayDate->subDay();
        } elseif ($request->has('nextDate')) {
            $displayDate->addDay();
        } else {
            $displayDate = Carbon::now();
        }

        $shop = Auth::user()->shops()->first();;
        $reservations = null;

        if ($shop) {
            $reservations = Reservation::with('user')
                ->where('shop_id', $shop->id)
                ->whereDate('date', $displayDate)
                ->orderBy('date', 'asc')
                ->orderBy('time', 'asc')
                ->paginate(15);
        }

        return view('writer.shop_reservation', compact('displayDate', 'reservations'));
    }

    public function update(Request $request)
    {
        $reservation = $request->all();
        $reservation['number'] = str_replace('人', '', $reservation['number']);
        unset($reservation['_token']);
        Reservation::find($request->id)->update($reservation);

        return back()->with('update', '予約情報を更新しました');
    }

    public function destroy(Request $request)
    {
        Reservation::find($request->id)->delete();

        return back()->with('delete', '予約情報を削除しました');
    }
}
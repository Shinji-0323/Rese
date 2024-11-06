<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\AdminShop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WriterController extends Controller
{
    public function editShow()
    {
        $shops = Shop::all();

        $shopRepresentative = Auth::user()->shopRepresentative;
        $shop = null;

        if ($shopRepresentative) {
            $shop = $shopRepresentative->shop;
        }

        return view('writer.shop_edit', compact('shops', 'shop'));
    }

    public function create_and_edit(Request $request)
    {
        $shopRepresentative = Auth::user()->shopRepresentative;

        if ($shopRepresentative) {
            $shopData = $request->except(['_token', 'image_url']);

            if ($request->hasFile('image_url')) {
                $path = $request->file('image_url')->store('public/shop_images');/* ('reservationsystem-restaurant', 's3'); */
                $shopData['image_url'] = Storage::/* disk('s3')-> */url($path);
            }

            Shop::find($shopRepresentative->shop_id)->update($shopData);
            return back()->with('success', '店舗情報を更新しました。');
        } else {
            $shopData = $request->all();

            if ($request->hasFile('image_url')) {
                $path = $request->file('image_url')->store('public/shop_images');/* ('reservationsystem-restaurant', 's3'); */
                $shopData['image_url'] = Storage::/* disk('s3')-> */url($path);
            }

            $createdShop = Shop::create($shopData);

            return back()->with('success', '店舗情報を作成しました。');
        }
    }

    public function reservationShow(Request $request)
    {
        $displayDate = Carbon::parse($request->input('displayDate'));

        if ($request->has('prevDate')) {
            $displayDate->subDay();
        }

        if ($request->has('nextDate')) {
            $displayDate->addDay();
        }

        $shopRepresentative = Auth::user()->shopRepresentative;
        $reservations = null;

        if ($shopRepresentative) {
            $reservations = Reservation::with('user')
                ->where('shop_id', $shopRepresentative->shop_id)
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
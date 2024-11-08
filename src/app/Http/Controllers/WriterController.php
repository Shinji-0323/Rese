<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WriterController extends Controller
{
    public function editShow()
    {
        $shops = Shop::all();

        $shop = Auth::user()->shops()->first();

        return view('writer.shop_edit', compact('shops', 'shop'));
    }

    public function create_and_edit(Request $request)
    {

        $shopData = $request->except(['_token', 'image_url']);

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('public/shop_images');/* ('reservationsystem-restaurant', 's3'); */
            $shopData['image_url'] = Storage::/* disk('s3')-> */url($path);
        }

        $shop = Auth::user()->shops()->first();
        if ($shop) {
            $shop->update($shopData);
            return back()->with('success', '店舗情報を更新しました。');
        } else {
            $createdShop = Shop::create($shopData);

            Auth::user()->shops()->attach($createdShop->id);

            return back()->with('success', '店舗情報を作成しました。');
        }
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
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;

class WriterController extends Controller
{
    public function index()
    {
        return view('writer.shop_edit');
    }

    public function showReservations()
    {
        $reservations = Reservation::all(); // すべての予約を表示（フィルターも可）
        return view('writer.shop_reservation', compact('reservations'));
    }

    public function store(Request $request)
    {
        // 店舗情報の保存
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Shop::create($request->all());

        return redirect()->back()->with('success', '店舗が登録されました');
    }

    public function update(Request $request, $id)
    {
        // 店舗情報の更新
        $shop = Shop::find($id);
        $shop->update($request->all());

        return redirect()->back()->with('success', '店舗情報が更新されました');
    }
}
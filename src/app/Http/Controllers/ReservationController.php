<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Favorite;
use App\Models\Review;

class ReservationController extends Controller
{
    public function reservation(ReservationRequest $request)
    {
        $form = $request->all();
        unset($form['_token']);
        Reservation::create($form);
        return redirect('/done');
    }

    public function edit($id)
    {
        $reservation = Reservation::find($id);

        $shop = Shop::find($reservation->shop_id);
        $user = Auth::user();

        $backRoute = '/mypage';

        return view('detail', compact('reservation', 'user', 'shop', 'backRoute'));
    }

    public function destroy(Request $request)
    {
        $reservation_id = $request->reservation_id;
        Reservation::find($reservation_id)->delete();
        return redirect('/my_page');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'number' => 'required|integer|min:1',
        ]);

        $reservation = Reservation::find($id);

        $reservation->date = $request->input('date');
        $reservation->time = $request->input('time');
        $reservation->number = $request->input('number');
        $reservation->save();

        return redirect('/done');
    }

    public function showQrCode($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        $qrData = json_encode([
            'reservation_id' => $reservation->id,
            'user_id' => $reservation->user_id,
            'timestamp' => now()->timestamp,
        ]);

        $qrCode = QrCode::size(200)->generate($qrData);

        return view('qr_code', compact('qrCode', 'reservation'));
    }

    public function verifyQrCode($reservation_id)
    {
        $reservation = Reservation::find($reservation_id);

        if ($reservation) {
            return view('admin.verify_qr', ['reservation' => $reservation]);
        } else {
            return redirect()->route('error.page')->with('error', '予約が見つかりません');
        }
    }
}

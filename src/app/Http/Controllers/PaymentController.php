<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Exception;

class PaymentController extends Controller
{
    public function showPaymentForm()
    {
        return view('payment.form');
    }

    public function processPayment(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret')); // シークレットキーを設定

        $amount = (int)$request->input('amount');

        try {
            // 支払い処理を行う
            $charge = Charge::create([
                'amount' => $amount,
                'currency' => 'jpy',
                'source' => $request->input('stripeToken'), // トークン（後で設定）
                'description' => '注文の支払い',
            ]);

            return back()->with('success_message', '支払いが完了しました！');
        } catch (Exception $e) {
            return back()->withErrors('エラーが発生しました: ' . $e->getMessage());
        }
    }
}

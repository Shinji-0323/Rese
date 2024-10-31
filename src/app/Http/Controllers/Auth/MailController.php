<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;

class MailController extends Controller
{
    public function unverified()
    {
        return view('auth.verified');
    }

    public function verify_complete(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/thanks');
    }

    public function retransmission(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '確認用メールが送信されました!');
    }
}

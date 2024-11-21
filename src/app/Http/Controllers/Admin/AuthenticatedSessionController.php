<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Models\Admin;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('admin.login');
    }

    public function store(LoginRequest $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Auth::guard('admin')->attempt($credentials)) {
            if (!$admin->hasVerifiedEmail()) {
                // 未認証の場合
                $admin->sendEmailVerificationNotification();
                Auth::guard('admin')->logout(); // 一旦ログアウトして認証を求める
                return redirect()->route('admin.verification.notice')
                    ->with('status', 'verification-link-sent');
            }

            // 認証済みの場合
            if ($admin->role === 'admin') {
                return redirect()->route('admin.user.index');
            } elseif ($admin->role === 'store_manager') {
                return redirect()->route('confirm-shop-reservation');
            }
        }

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません。',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}

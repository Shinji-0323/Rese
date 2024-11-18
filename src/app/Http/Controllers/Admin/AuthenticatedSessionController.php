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

        if (Auth::guard('admin')->attempt($credentials)) {
            if (!$admin->hasVerifiedEmail()) {
                Auth::guard('admin')->logout(); // 未確認管理者はログアウト
                $admin->sendEmailVerificationNotification(); // 確認メールの再送信
                return back()->with('message', 'メールアドレスが確認されていません。確認メールを再送信しました。');
            }

            if ($admin->role === 'admin') {
                return redirect()->route('admin.user.index');
            } elseif ($admin->role === 'store_manager') {
                return redirect()->route('confirm-shop-reservation'); // 店舗代表者用のリダイレクト先
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

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
        $credentials = $request->only('email', 'password');
        $admin = Admin::where('email', $request->email)->first();

        Auth::guard('admin')->login($admin);
        $admin->sendEmailVerificationNotification();

        return redirect()->route('admin.verification.notice')->with('status', 'verification-link-sent');
    }

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}

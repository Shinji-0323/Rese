<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Admin;

class RegisteredUserController extends Controller
{
    public function create()
    {
        $roles = ['admin' => '管理者', 'store_manager' => '店舗代表者'];

        return view('admin.register', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string',
            'role' => 'required'
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::guard('admin')->login($admin);

        $admin->sendEmailVerificationNotification();

        return redirect()->route('admin.verification.notice')->with('status', 'verification-link-sent');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.user');
    }

    public function createStoreOwner(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ]);

        // 店舗代表者を作成
        $storeOwner = new User();
        $storeOwner->name = $request->input('name');
        $storeOwner->email = $request->input('email');
        $storeOwner->password = bcrypt($request->input('password'));
        $storeOwner->role_id = Role::where('name', 'store_owner')->first()->id;
        $storeOwner->save();

        return redirect()->back()->with('success', '店舗代表者が作成されました');
    }
}

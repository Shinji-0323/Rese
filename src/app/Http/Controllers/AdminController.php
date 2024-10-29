<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Shop;
use App\Models\User;

class AdminController extends Controller
{
    public function create()
    {
        return view('admin.register_shopRepresentative');
    }

    public function register(Request $request)
    {
        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->email_verified_at = now();
        $admin->password = Hash::make($request->password);
        $admin->role = $request->role;
        $admin->save();

        return view('admin.done');
    }

    public function userShow()
    {
        $shops = Shop::all();
        $shop_list = array();
        foreach( $shops as $shop) {
            $shop_list[$shop->name] = $shop->id;
        }

        $admins = Admin::all();
        $admin_list = array();
        foreach( $admins as $admin ) {
            $shops = $admin->shops()->get();
            $tmp_shop_list = array();
            foreach( $shops as $shop ) {
                $tmp_shop_list[] = [
                    'shop_id'=>$shop->id,
                    'shop_name'=>$shop->name
                ];
            }
            $admin_list[] = [
                'id'=>$admin->id,
                'name'=>$admin->name,
                'email'=>$admin->email,
                'role'=>$admin->role,
                'shops'=>$tmp_shop_list
            ];
        }

        return view('admin.user', compact('admin_list', 'shop_list'));
    }

    public function search(Request $request)
    {
        $roleId = $request->input('role_id');
        $query = User::with('roles');

        if ($roleId === 'all') {
            $users = $query->get();
        } elseif ($roleId === 'user') {
            $users = $query->doesntHave('roles')->get();
        } else {
            $users = $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('roles.id', $roleId);
            })->get();
        }

        return response()->json($users);
    }
}

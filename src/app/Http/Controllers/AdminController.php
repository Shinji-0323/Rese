<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddAdminRequest;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;
use App\Models\Shop;
use App\Models\User;

class AdminController extends Controller
{
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

            $role = $admin->role;
            if ($role === 'admin') {
                $role = '管理者';
            } elseif ($role === 'store_manager') {
                $role = '店舗代表者';
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

    public function isAdmin($role)
    {
        return $role === 'admin';
    }

    public function store(AddAdminRequest $request)
    {
        if (!$this->isAdmin(Auth::user()->role)) return redirect('admin/login');

        $message = '';
        $admin = Admin::select()->EmailSearch($request->email)->get()->toArray();
        if ( empty($admin) ) {
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => 'xxxxxxxx'
            ]);
            if ( 1 == $request->role ) {
                $admin->shops()->sync([$request->shop]);
            }
            $message = '新規登録を行いました。';
        } else {
            $admin = Admin::find($admin[0]['id']);
            $admin->update([
                'role' => $request->role
            ]);
            if ( 1 == $request->role ) {
                $admin->shops()->syncWithoutDetaching([$request->shop]);
            } else {
                $admin->shops()->sync([]);
            }
            $message = '登録情報を更新しました。';
        }
        return redirect('admin.user')->with('message', $message);
    }

    public function destroy(Request $request)
    {
        if (!$this->isAdmin(Auth::user()->role)) return redirect('admin/login');

        $admin_id = $request->admin_id;
        $admin = Admin::find($admin_id);
        $admin->shops()->detach();
        $admin->delete();

        $message = '管理者を削除しました。';
        return redirect('admin.user.index')->with('message', $message);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

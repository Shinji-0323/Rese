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
        $adminId = $request->input('admin_id');
        $isUpdate = !empty($adminId);

        if ($isUpdate) {
            // 更新処理
            $admin = Admin::findOrFail($adminId);

            // 名前とメールアドレスの変更は許可せず、他の項目のみ更新
            $admin->update([
                'role' => $request->input('role'),
                'password' => $request->input('password') ? Hash::make($request->input('password')) : $admin->password,
            ]);

            // 店舗の同期（役割が店舗代表者の場合のみ）
            if ($request->input('role') === 'store_manager') {
                $admin->shops()->syncWithoutDetaching([$request->input('shop')]);
            } else {
                $admin->shops()->sync([]);
            }

            $message = '登録情報を更新しました。';
        } else {
            // 新規登録処理
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admins,email',
                'password' => 'required|string',
                'role' => 'required',
                'shop' => 'required_if:role,store_manager',
            ]);

            $admin = Admin::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'role' => $validatedData['role'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // 店舗の登録（役割が店舗代表者の場合のみ）
            if ($validatedData['role'] === 'store_manager') {
                $admin->shops()->attach($validatedData['shop']);
            }

            $message = '新規登録を行いました。';
        }

        return redirect()->route('admin.user.index')->with('message', $message);
    }
    
    public function destroy(Request $request)
    {
        if (!$this->isAdmin(Auth::user()->role)) return redirect('admin/login');

        $admin_id = $request->admin_id;
        $admin = Admin::find($admin_id);
        $admin->shops()->detach();
        $admin->delete();

        $message = '管理者を削除しました。';
        return redirect()->route('admin.user.index')->with('message', $message);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

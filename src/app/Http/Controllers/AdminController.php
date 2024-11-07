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
        $admin = Auth::user();
        $shop_list = array();
        $shops = Shop::all();
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

        // 役割が店舗代表者の場合は、一覧表示のみを許可
        if ($admin->role === 'store_manager') {
            $isUpdate = false;
            return view('admin.user', compact('admin', 'admin_list', 'isUpdate'));
        }
        
        // 管理者のときは新規登録や更新も表示する
        $isUpdate = false;
        return view('admin.user', compact('admin', 'admin_list', 'shop_list', 'isUpdate'));
    }

    public function store(AddAdminRequest $request)
    {
        // ログインしているユーザーの役割を確認
        $admin = Auth::user();

        // 店舗代表者がアクセスした場合、アクセスを制限
        if ($admin->role === 'store_manager') {
            return redirect()->route('admin.user.index')->with('message', '権限がありません。');
        }

        // 以下は新規登録と更新の処理
        $adminId = $request->input('admin_id');
        $isUpdate = !empty($adminId);

        if ($isUpdate) {
            // 更新処理
            $adminToUpdate = Admin::findOrFail($adminId);

            $validatedData = $request->validate([
                'role' => 'required',
                'password' => 'nullable|string',
                'shop' => 'required_if:role,store_manager',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('admins', 'email')->ignore($adminId)
                ],
            ]);
            
            $adminToUpdate->update([
                'role' => $request->input('role'),
                'password' => $request->input('password') ? Hash::make($request->input('password')) : $adminToUpdate->password,
            ]);

            if ($request->input('role') === 'store_manager') {
                $adminToUpdate->shops()->sync([$request->input('shop')]);
            } else {
                $adminToUpdate->shops()->sync([]);
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

            $newAdmin = Admin::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'role' => $validatedData['role'],
                'password' => Hash::make($validatedData['password']),
            ]);

            if ($validatedData['role'] === 'store_manager') {
                $newAdmin->shops()->attach($validatedData['shop']);
            }

            $message = '新規登録を行いました。';
        }

        return redirect()->route('admin.user.index')->with('message', $message);
    }

    public function destroy(Request $request)
    {
        // ログインしているユーザーの役割を確認
        $admin = Auth::user();

        // 店舗代表者がアクセスした場合、アクセスを制限
        if ($admin->role === 'store_manager') {
            return redirect()->route('admin.user.index')->with('message', '権限がありません。');
        }

        $adminId = $request->input('admin_id');
        Admin::findOrFail($adminId)->delete();

        return redirect()->route('admin.user.index')->with('message', '管理者を削除しました。');
    }
}
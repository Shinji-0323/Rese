<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;
use App\Models\Shop;
use App\Models\User;

class AdminController extends Controller
{
    public function showRegisterForm()
    {
        $admins = Admin::all();
        return view('admin.register', compact('admins'));
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required']
        ]);

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->email_verified_at = now();
        $admin->password = Hash::make($request->password);
        $admin->role = $request->role;
        $admin->save();

        return view('admin.done');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function storeLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 管理者の認証を試行
        if (Auth::guard('admin')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            // 認証成功

            // ユーザーが管理者かどうかを確認
            $admin = Auth::guard('admin')->user();
            if ($admin->role === '管理者' || $admin->role === '店舗代表者') {
                // ログイン成功時にセッションIDを再生成
                $request->session()->regenerate();

                // 管理者ページにリダイレクト
                return redirect()->intended('admin/user/index');
            } else {
                // 役割が管理者でない場合はログアウトしてリダイレクト
                Auth::logout();
                return redirect('/admin/login')->withErrors([
                    'email' => '管理者のみアクセスできます。',
                ]);
            }
        }

        // 認証失敗時
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
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
        return redirect('/admin/index')->with('message', $message);
    }

    public function destroy(Request $request)
    {
        if (!$this->isAdmin(Auth::user()->role)) return redirect('admin/login');

        $admin_id = $request->admin_id;
        $admin = Admin::find($admin_id);
        $admin->shops()->detach();
        $admin->delete();

        $message = '管理者を削除しました。';
        return redirect('/admin/index')->with('message', $message);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

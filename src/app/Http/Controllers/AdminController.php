<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\AddAdminRequest;
use App\Models\Admin;
use App\Models\Shop;
use App\Models\User;
use App\Models\AdminShop;
use App\Http\Traits\Content;
use App\Mail\Notification;

class AdminController extends Controller
{
    use Content;

    public function userShow()
    {
        if (!$this->isAdmin(Auth::user()->role)) return redirect('admin/login');

        //店舗一覧
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

        return view('admin.user', compact('admin_list', 'shop_list'));
    }

    public function store(AddAdminRequest $request)
    {
        if (!$this->isAdmin(Auth::user()->role)) return redirect('admin.login');

        $message = '新規登録を行いました。';

        // 新規作成
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request['password']),
            'email_verified_at' => now(),
        ]);

        // 店舗責任者の場合は店舗を登録
        if ($request->role === 'store_manager') {
            $admin->shops()->attach($request->shop);
        }

        return redirect()->route('admin.user.index')->with('message', $message);
    }

    public function destroy(Request $request)
    {
        if (!$this->isAdmin(Auth::user()->role)) return redirect('admin.login');

        $admin_id = $request->admin_id;
        $admin = Admin::find($admin_id);
        $admin->shops()->detach();  // 中間テーブルから関連レコードを削除
        $admin->delete();

        $message = '管理者を削除しました。';
        return redirect()->route('admin.user.index')->with('message', $message);
    }

    public function email_notification()
    {
        return view('admin.email_notification');
    }

    public function sendNotification(Request $request)
    {
        $destination = $request->input('destination');
        $messageContent = $request->input('message');

        $users = collect();

        if ($destination === 'all') {
            $users = User::all()->merge(Admin::all());
        } elseif ($destination === 'user') {
            $users = User::all();
        } elseif ($destination === 'writer') {
            $users = Admin::where('role', 'store_manager')->get();
        } elseif ($destination === 'admin') {
            $users = Admin::where('role', 'admin')->get();
        }

        foreach ($users as $user) {
            Mail::to($user->email)->send(new Notification($user, $messageContent));
        }
        return redirect()->route('admin.send_notification')->with('success', "メールを送信しました。");
    }

}
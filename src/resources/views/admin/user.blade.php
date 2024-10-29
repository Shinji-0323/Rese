@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/index_user.css') }}">
@endsection

@section('content')
    <div class="mail__wrap">
        <div class="mail__content">お知らせメール作成</div>
        <a class="mail__button" href="{{ url('admin/make_announcement') }}">作成</a>
    </div>

    <div class="admin__wrap">
        <div class="admin__content">管理者登録・変更</div>
        <form class="admin__content__form" method="POST" action="{{ url('admin/add') }}">
            @csrf
            <div>
                {{ session('message') }}
            </div>
            <div class="content__header">
                <label for="name" class="">名前</label>
                <input type="text" name="name" value="{{ old('name') }}">
                <div class="text-red-600">
                    @error('name')
                        ※{{ $message }}
                    @enderror
                </div>
            </div>
            <div class="content__header">
                <label for="email" class="">Email</label>
                <input type="text" name="email" value="{{ old('email') }}">
                <div class="text-red-600">
                    @error('email')
                        ※{{ $message }}
                    @enderror
                </div>
            </div>
            <div class="content__header">
                <label for="role" class="">役割</label>
                <select name="role">
                    <option value=""  selected></option>
                </select>
            </div>
            <div class="content__header">
                <label for="shop" class="">店舗</label>
                <select name="shop">
                    @foreach ( $shop_list as $key => $value )
                    <option value="{{ $value }}" @if($value === (int)old('shop')) selected @endif>{{ $key }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ml-8">
                <button class="admin__button" type="submit">
                    登録・変更
                </button>
            </div>
        </form>
    </div>

    <div class="table__wrap">
        <table class="user__table">
            <tr>
                <th class="table__header header-no">Id</th>
                <th class="table__header header-name">名前</th>
                <th class="table__header header-email">email</th>
                <th class="table__header header-role">役割</th>
                <th class="table__header header-shop">店舗</th>
            </tr>
            @foreach ($admin_list as $admin)
                <tr>
                    <td class="table__data">{{ $admin['id'] }}</td>
                    <td class="table__data">{{ $admin['name'] }}</td>
                    <td class="table__data data-email">{{ $admin['email'] }}</td>
                    <td class="table__data">{{ $admin['role'] }}</td>
                    <td class="table__data">
                        @foreach ($admin['shops'] as $shop)
                        {{ $admin['shops'] }}
                        @endforeach
                    </td>
                    <td>
                    <form method="POST" action="{{ url('admin/delete') }}">
                        @csrf
                        <input type="hidden" name="admin_id" value="{{ $admin['id'] }}">
                        <button class="submit" type="submit">
                            削除
                        </button>
                    </form>
                </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
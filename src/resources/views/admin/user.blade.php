@extends('admin.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/index_user.css') }}">
@endsection

@section('content')
    <div class="mail__wrap">
        <div class="mail__content">お知らせメール作成</div>
        <button class="mail__button" href="{{ url('admin/make_announcement') }}">作成</button>
    </div>

    <div class="admin__wrap">
        <div class="admin__content">管理者登録・変更</div>
        <form class="admin__content__form" method="POST" action="{{ route('admin.add') }}">
            @csrf
            <div>
                {{ session('message') }}
            </div>
            <div class="content__header">
                <label for="name" class="content__header__label">名前</label>
                <input class="content__header__input" type="text" name="name" value="{{ old('name') }}">
                <div class="content__header__error">
                    @error('name')
                        ※{{ $message }}
                    @enderror
                </div>
            </div>
            <div class="content__header">
                <label for="email" class="content__header__label">Email</label>
                <input class="content__header__input" type="text" name="email" value="{{ old('email') }}">
                <div class="content__header__error">
                    @error('email')
                        ※{{ $message }}
                    @enderror
                </div>
            </div>
            <div class="content__header">
                <label for="password" class="content__header__label">パスワード</label>
                <input class="content__header__input" type="password" name="password" value="{{ old('password') }}">
                <div class="content__header__error">
                    @error('password')
                        ※{{ $message }}
                    @enderror
                </div>
            </div>
            <div class="content__header">
                <label for="role" class="content__header__label">役割</label>
                <select class="content__header__select" name="role">
                    @foreach ( App\Consts\RoleConst::ROLE_LIST as $key => $val )
                    <option value="{{ $key }}" @if($key == old('role')) selected @endif>{{ $val }}</option>
                    @endforeach
                </select>
                <div class="content__header__error">
                    @error('role')
                        ※{{ $message }}
                    @enderror
                </div>
            </div>
            <div class="content__header">
                <label for="shop" class="content__header__label">店舗</label>
                <select class="content__header__select" name="shop">
                    @foreach ( $shop_list as $key => $value )
                    <option value="{{ $value }}" @if($value === (int)old('shop')) selected @endif>{{ $key }}</option>
                    @endforeach
                </select>
            </div>
            <div class="content__button">
                <button class="admin__button" type="submit">
                    登録・変更
                </button>
            </div>
        </form>
    </div>

    <div class="user__wrap">
        <div class="user__title">管理者一覧</div>
        <table class="user__table">
            <tr>
                <th class="table__header header-no">Id</th>
                <th class="table__header header-name">名前</th>
                <th class="table__header header-email">email</th>
                <th class="table__header header-role">役割</th>
                <th class="table__header header-shop">店舗</th>
                <th class="table__header header-content"></th>
            </tr>
            @foreach ($admin_list as $admin)
                <tr>
                    <td class="table__data">{{ $admin['id'] }}</td>
                    <td class="table__data">{{ $admin['name'] }}</td>
                    <td class="table__data">{{ $admin['email'] }}</td>
                    <td class="table__data">
                        @if ($admin['role'] === 'admin')
                            管理者
                        @elseif ($admin['role'] === 'store_manager')
                            店舗代表者
                        @endif
                    </td>
                    <td class="table__data">
                        @foreach ($admin['shops'] as $shop)
                        {{ $shop['shop_name'] }}
                        @endforeach
                    </td>
                    <td class="table__button">
                    <form method="POST" action="{{ route('admin.delete') }}">
                        @csrf
                        <input type="hidden" name="admin_id" value="{{ $admin['id'] }}">
                        <button class="user__button" type="submit">
                            削除
                        </button>
                    </form>
                </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
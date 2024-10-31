@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
    <script src="https://kit.fontawesome.com/706e1a4697.js" crossorigin="anonymous"></script>
@endsection

@section('content')
    <div class="auth__wrap">
        <div class="auth__header">
            Admin Registration
        </div>
        <form action="/admin/register" method="post" class="form__item">
            @csrf
            <div class="form__item-user">
                <i class="fa-solid fa-user fa-2x"> <input type="text" class="form__input-item" name="name" placeholder="Name" value="{{ old('name') }}"></i>
            </div>
            <div class="error__item">
                @error('name')
                    <span class="error__message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form__item-mail">
                <i class="fa-sharp fa-solid fa-envelope fa-2x"> <input type="email" class="form__input-item" name="email" placeholder="Email" value="{{ old('email') }}"></i>
            </div>
            <div class="error__item">
                @error('email')
                    <span class="error__message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form__item-key">
                <i class="fa-solid fa-lock fa-2x"> <input type="password" class="form__input-item" name="password" placeholder="Password"></i>
            </div>
            <div class="error__item">
                @error('password')
                    <span class="error__message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form__item-role">
                <i class="fa-solid fa-shield-halved fa-2x"> 
                    <select class="form__item-select" name="role" id="">
                        <option disabled selected>選択してください</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->role }}" {{ old('role')==$admin->role ? 'selected' : '' }}>{{
                        $admin->role }}</option>
                        @endforeach
                    </select>
                </i>
            </div>
            <div class="error__item">
                @error('role')
                    <span class="error__message">{{ $message }}</span>
                @enderror
            </div>
            <input class="form__item-button" type="submit" value="登録">
        </form>
    </div>
@endsection
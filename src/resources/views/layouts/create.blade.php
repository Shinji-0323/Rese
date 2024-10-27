@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <h1>店舗を登録する</h1>
    <form action="/store" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name">店舗名:</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="name">エリア:</label>
            <input type="text" name="region" id="region" required>
        </div>
        <div>
            <label for="name">ジャンル:</label>
            <input type="text" name="genre" id="genre" required>
        </div>
        <div>
            <label for="name">説明:</label>
            <input type="textarea" name="description" id="description" required>
        </div>
        <div>
            <label for="image">店舗の写真:</label>
            <input type="file" name="image" id="image" accept="image/*" required>
        </div>

        <button type="submit">店舗を登録</button>
    </form>
@endsection
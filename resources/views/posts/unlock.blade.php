@extends('layouts.app')
@section('content')
    <div class="card">
        <h3>Пост закрыт</h3>
        <p>Этот пост доступен только по специальному коду. Введите его:</p>
        <form method="POST" action="{{ route('posts.unlock', $post) }}">
            @csrf
            <input name="code" placeholder="код доступа">
            @error('code')<div class="err">{{ $message }}</div>@enderror
            <button style="margin-top:10px">Открыть</button>
        </form>
    </div>
@endsection
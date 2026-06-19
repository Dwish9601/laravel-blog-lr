@extends('layouts.app')
@section('title', 'редактировать пост')
@section('content')
    <h2 style="color:#c9a66b">Редактировать пост</h2>
    <form method="POST" action="{{ route('posts.update', $post) }}">
        @csrf
        @method('PUT')
        @include('posts._form')
        <button>Сохранить</button>
    </form>
@endsection
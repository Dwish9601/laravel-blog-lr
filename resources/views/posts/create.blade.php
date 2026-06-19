@extends('layouts.app')
@section('title', 'новый пост')
@section('content')
    <h2 style="color:#c9a66b">Написать пост</h2>
    <form method="POST" action="{{ route('posts.store') }}">
        @csrf
        @include('posts._form')
        <button>Опубликовать</button>
    </form>
@endsection
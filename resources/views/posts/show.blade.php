@extends('layouts.app')
@section('title', $post->title)
@section('content')
    @if(session('code'))
        <div class="flash">Пост скрыт. Код доступа: <b>{{ session('code') }}</b>. Передайте его тем, кому хотите показать.</div>
    @endif

    <article class="card">
        <h3 style="font-size:26px">{{ $post->title }}</h3>
        <div class="meta">
            {{ $post->author->name }} · {{ $post->created_at->format('d.m.Y H:i') }}
            · <span style="color:#c9a66b">{{ $post->visibility }}</span>
        </div>
        <div style="white-space:pre-wrap">{{ $post->body }}</div>
        <div style="margin-top:14px">
            @foreach($post->tags as $t)
                <a class="tag" href="{{ route('tag.show', $t) }}">#{{ $t->name }}</a>
            @endforeach
        </div>

        @auth
            @if($post->isOwnedBy(auth()->user()))
                <div style="margin-top:18px">
                    <a class="btn btn-ghost" href="{{ route('posts.edit', $post) }}">править</a>
                    <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display:inline"
                          onsubmit="return confirm('Точно удалить?')">
                        @csrf @method('DELETE')
                        <button class="btn-ghost" style="color:#e07a7a;border-color:#e07a7a">удалить</button>
                    </form>
                </div>
            @endif
        @endauth
    </article>

    <h3 style="color:#c9a66b">Комментарии ({{ $post->comments->count() }})</h3>
    @foreach($post->comments as $c)
        <div class="comment">
            <div class="meta"><b>{{ $c->author->name }}</b> · {{ $c->created_at->diffForHumans() }}
                @auth
                    @if($c->user_id === auth()->id() || $post->isOwnedBy(auth()->user()))
                        <form method="POST" action="{{ route('comments.destroy', $c) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn-ghost" style="padding:2px 8px;font-size:12px">✕</button>
                        </form>
                    @endif
                @endauth
            </div>
            {{ $c->body }}
        </div>
    @endforeach

    @auth
        <form method="POST" action="{{ route('comments.store', $post) }}" style="margin-top:16px">
            @csrf
            <textarea name="body" placeholder="Написать комментарий..."></textarea>
            <button style="margin-top:6px">Отправить</button>
        </form>
    @endauth
@endsection
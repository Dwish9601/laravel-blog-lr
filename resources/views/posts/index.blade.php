@extends('layouts.app')
@section('title', isset($currentTag) ? '#'.$currentTag : 'публичные посты')
@section('content')
    <h2 style="color:#c9a66b">
        @isset($currentTag) посты с тегом #{{ $currentTag }} @else все публичные посты @endisset
    </h2>

    <div style="margin-bottom:20px">
        @foreach($tags as $t)
            <a class="tag" href="{{ route('tag.show', $t) }}">#{{ $t->name }} ({{ $t->posts_count }})</a>
        @endforeach
    </div>

    @forelse($posts as $p)
        <article class="card">
            <h3><a href="{{ route('posts.show', $p) }}">{{ $p->title }}</a></h3>
            <div class="meta">
                {{ $p->author->name }} · {{ $p->created_at->diffForHumans() }}
                @foreach($p->tags as $t)
                    <a class="tag" href="{{ route('tag.show', $t) }}">#{{ $t->name }}</a>
                @endforeach
            </div>
            <p>{{ Str::limit($p->body, 220) }}</p>
        </article>
    @empty
        <p style="color:#8a8678">Пока пусто.</p>
    @endforelse

    {{ $posts->links() }}
@endsection
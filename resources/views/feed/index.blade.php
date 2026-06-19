@extends('layouts.app')
@section('title', 'моя лента')
@section('content')
    <h2 style="color:#c9a66b">Лента — посты тех, на кого вы подписаны</h2>
    @forelse($posts as $p)
        <article class="card">
            <h3><a href="{{ route('posts.show', $p) }}">{{ $p->title }}</a></h3>
            <div class="meta">{{ $p->author->name }} · {{ $p->created_at->diffForHumans() }}</div>
            <p>{{ Str::limit($p->body, 260) }}</p>
        </article>
    @empty
        <p style="color:#8a8678">Подпишитесь на кого-нибудь — <a href="{{ route('people') }}" style="color:#c9a66b">список людей</a>.</p>
    @endforelse
    {{ $posts->links() }}
@endsection
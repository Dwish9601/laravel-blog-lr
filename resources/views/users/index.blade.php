@extends('layouts.app')
@section('content')
    <h2 style="color:#c9a66b">Люди</h2>
    @foreach($people as $u)
        <div class="card" style="display:flex;justify-content:space-between;align-items:center">
            <div>
                <b>{{ $u->name }}</b>
                <div class="meta">постов: {{ $u->posts_count }} · подписчиков: {{ $u->followers_count }}</div>
            </div>
            <form method="POST" action="{{ route('follow.toggle', $u) }}">
                @csrf
                <button class="btn-ghost">
                    {{ auth()->user()->isFollowing($u) ? 'отписаться' : 'подписаться' }}
                </button>
            </form>
        </div>
    @endforeach
    {{ $people->links() }}
@endsection
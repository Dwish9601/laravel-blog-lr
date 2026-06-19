<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Блог — @yield('title', 'лента')</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Georgia, serif; background: #1e1e22; color: #d8d4c8; }
        header { background: #2b2b31; padding: 14px 30px; border-bottom: 1px solid #3a3a42; display:flex; justify-content:space-between; align-items:center; }
        header a { color: #c9a66b; text-decoration: none; margin-right: 18px; }
        header a:hover { color: #e8c98a; }
        main { max-width: 880px; margin: 30px auto; padding: 0 20px; }
        .card { background: #26262c; padding: 18px 22px; border-radius: 4px; margin-bottom: 18px; border-left: 3px solid #c9a66b; }
        .card h3 { margin: 0 0 8px; }
        .card h3 a { color: #e8e3d3; text-decoration: none; }
        .meta { font-size: 13px; color: #8a8678; margin-bottom: 10px; }
        .tag { display:inline-block; background:#3a3a42; color:#c9a66b; padding:2px 9px; border-radius:10px; font-size:12px; margin-right:4px; text-decoration:none; }
        input, textarea, select { width:100%; background:#1a1a1f; border:1px solid #3a3a42; color:#d8d4c8; padding:9px; font-family:inherit; border-radius:3px; }
        textarea { min-height: 140px; resize: vertical; }
        button, .btn { background:#c9a66b; color:#1e1e22; border:0; padding:9px 18px; cursor:pointer; font-weight:bold; border-radius:3px; text-decoration:none; display:inline-block; }
        button:hover, .btn:hover { background:#e8c98a; }
        .btn-ghost { background:transparent; color:#c9a66b; border:1px solid #c9a66b; }
        .flash { background:#3a3a42; border-left:3px solid #8ac97a; padding:10px 14px; margin-bottom:18px; }
        .err { color:#e07a7a; font-size:13px; }
        .comment { background:#1a1a1f; padding:10px 14px; border-radius:3px; margin-top:8px; }
    </style>
</head>
<body>
<header>
    <div>
        <a href="{{ route('home') }}">главная</a>
        @auth
            <a href="{{ route('feed') }}">моя лента</a>
            <a href="{{ route('posts.create') }}">+ написать</a>
            <a href="{{ route('people') }}">люди</a>
        @endauth
    </div>
    <div>
        @auth
            <span style="color:#8a8678">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf <button class="btn-ghost" style="padding:4px 10px">выйти</button>
            </form>
        @else
            <a href="{{ route('login') }}">вход</a>
            <a href="{{ route('register') }}">регистрация</a>
        @endauth
    </div>
</header>
<main>
    @if(session('ok'))<div class="flash">{{ session('ok') }}</div>@endif
    @yield('content')
</main>
</body>
</html>
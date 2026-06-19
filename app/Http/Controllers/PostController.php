<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;          
use App\Models\Tag;           
use App\Models\User;          
use Illuminate\Support\Str; 

class PostController extends Controller
{
    public function __construct()
    {
        // почти всё требует авторизации, кроме просмотра публичных
        $this->middleware('auth')->except(['show', 'publicIndex', 'unlock']);
    }

    public function publicIndex(Request $request)
    {
        $q = Post::where('visibility', 'public')->with(['author', 'tags']);

        // сортировка по тегу, если передан
        if ($request->filled('tag')) {
            $q->whereHas('tags', fn($x) => $x->where('name', $request->tag));
        }

        $posts = $q->latest()->paginate(12);
        $tags  = Tag::withCount('posts')->orderByDesc('posts_count')->get();

        return view('posts.index', compact('posts', 'tags'));
    }

    public function create()
    {
        return view('posts.create', ['post' => null, 'tags' => Tag::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'body'        => 'required|string|min:10',
            'visibility'  => 'in:public,private,by_request',
            'access_code' => 'nullable|string|max:50',
            'tags'        => 'nullable|string', // "php, laravel, web"
        ]);

        // если выбран "по запросу", а код не ввели — сгенерим сами
        if ($data['visibility'] === 'by_request' && empty($data['access_code'])) {
            $data['access_code'] = Str::lower(Str::random(8));
        }

        $post = $request->user()->posts()->create($data);
        $this->syncTags($post, $data['tags'] ?? '');

        if ($post->visibility === 'by_request') {
            session()->flash('code', $post->access_code);
        }

        return redirect()->route('posts.show', $post)->with('ok', 'Опубликовано');
    }

    public function show(Request $request, Post $post)
    {
        // публичный — всем, приватный — только автору, by_request — по коду
        if ($post->visibility === 'private' && (!$request->user() || !$post->isOwnedBy($request->user()))) {
            abort(403, 'Пост скрыт автором');
        }

        $code = session('unlock_'.$post->id);
        if ($post->visibility === 'by_request' && (!$request->user() || !$post->isOwnedBy($request->user())) && $code !== $post->access_code) {
            return view('posts.unlock', compact('post'));
        }

        $post->load(['author', 'tags', 'comments.author']);
        return view('posts.show', compact('post'));
    }

    public function unlock(Request $request, Post $post)
    {
        $request->validate(['code' => 'required|string']);
        if ($request->code !== $post->access_code) {
            return back()->withErrors(['code' => 'Неверный код доступа']);
        }
        session(['unlock_'.$post->id => $post->access_code]);
        return redirect()->route('posts.show', $post);
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $post->load('tags');  // Загрузите теги!
        return view('posts.edit', [
            'post' => $post,
            'tags' => Tag::all()
        ]); 
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'body'        => 'required|string|min:10',
            'visibility'  => 'in:public,private,by_request',
            'access_code' => 'nullable|string|max:50',
            'tags'        => 'nullable|string',
        ]);

        $post->update($data);
        $this->syncTags($post, $data['tags'] ?? '');

        return redirect()->route('posts.show', $post)->with('ok', 'Сохранено');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->route('feed')->with('ok', 'Удалено');
    }

    // превращаем "php, laravel, web" в связь с тегами
    private function syncTags(Post $post, string $raw): void
    {
        $names = array_filter(array_map('trim', explode(',', $raw)));
        $ids = [];
        foreach ($names as $n) {
            $tag = Tag::firstOrCreate(['name' => Str::lower($n)]);
            $ids[] = $tag->id;
        }
        $post->tags()->sync($ids);
    }
}

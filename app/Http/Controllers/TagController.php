<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(Tag $tag)
    {
        $posts = $tag->posts()
            ->where('visibility', 'public')
            ->with(['author', 'tags'])
            ->latest()
            ->paginate(12);

        return view('posts.index', [
            'posts'      => $posts,
            'tags'       => Tag::withCount('posts')->orderByDesc('posts_count')->get(),
            'currentTag' => $tag->name,
        ]);
    }
}
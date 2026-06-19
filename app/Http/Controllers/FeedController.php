<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function __invoke(Request $request)
    {
        $ids = $request->user()->followings()->pluck('users.id')->toArray();
        $ids[] = $request->user()->id;

        $posts = Post::with(['author', 'tags'])
            ->whereIn('user_id', $ids)
            ->where('visibility', 'public')
            ->latest()
            ->paginate(15);

        return view('feed.index', compact('posts'));
    }
}
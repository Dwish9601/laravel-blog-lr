<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $data = $request->validate(['body' => 'required|string|min:2|max:1000']);
        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $data['body'],
        ]);
        return back()->with('ok', 'Комментарий добавлен');
    }

    public function destroy(Comment $comment)
    {
        $me = auth()->user();
        if ($comment->user_id !== $me->id && $comment->post->user_id !== $me->id) {
            abort(403);
        }
        $postId = $comment->post_id;
        $comment->delete();
        return redirect()->route('posts.show', $postId)->with('ok', 'Удалено');
    }
}
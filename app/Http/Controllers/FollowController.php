<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(User $user)
    {
        $me = auth()->user();
        if ($me->id === $user->id) abort(400);

        if ($me->isFollowing($user)) {
            $me->followings()->detach($user->id);
            $msg = 'Отписка';
        } else {
            $me->followings()->attach($user->id);
            $msg = 'Подписка оформлена';
        }
        return back()->with('ok', $msg);
    }

    public function index()
    {
        $people = User::where('id', '!=', auth()->id())
            ->withCount(['followers', 'posts'])
            ->paginate(20);
        return view('users.index', compact('people'));
    }
}
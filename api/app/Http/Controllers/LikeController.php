<?php

namespace App\Http\Controllers;

use App\Post;
use App\Setting;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Post $post
     * @return void
     */
    public function __invoke(Request $request, Post $post)
    {
        if (!$post->is_public) {
            abort(404);
        }

        if (Setting::getAll()->like_enabled && !session("liked.{$post->id}")) {
            $post->increment('likes');
            session(["liked.{$post->id}" => true]);
        }

        return redirect()->back();
    }
}

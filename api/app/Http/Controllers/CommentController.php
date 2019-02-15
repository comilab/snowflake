<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\Setting;
use Illuminate\Http\Request;

class CommentController extends Controller
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

        $request->validate([
            'name' => 'required',
            'body' => 'required',
        ]);

        if (Setting::getAll()->comment_enabled) {
            $comment = new Comment($request->all());
            $post->comments()->save($comment);
        }

        return redirect()->back();
    }
}

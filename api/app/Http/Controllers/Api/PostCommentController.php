<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Post;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class);
        $this->authorizeResource(Comment::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {
        $query = $post->comments();

        return CommentResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post, Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Comment $comment)
    {
        $comment->delete();

        return new CommentResource($comment);
    }
}

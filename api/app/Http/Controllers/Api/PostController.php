<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Post as PostResource;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->user()->posts()->with(['tagged'])->withCount(['comments']);

        if ($request->filled('tags')) {
            $query->withAllTags($request->get('tags'));
        }

        if ($request->filled('keyword')) {
            $query->searchBy($request->get('keyword'));
        }

        if ($request->filled('sortBy')) {
            $direction = $request->get('descending') === 'true' ? 'desc' : 'asc';
            $query->orderBy($request->get('sortBy'), $direction);
        }
        $query->orderBy('id', 'desc');

        if ($request->filled('page')) {
            return PostResource::collection($query->paginate(12));
        }

        return PostResource::collection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => '',
            'body' => '',
            'is_public' => 'required|boolean',
            'created_at' => 'nullable|date',
            'tags' => 'array',
        ]);

        $post = new Post($request->all());
        $post->user_id = $request->user()->id;
        if ($request->filled('created_at')) {
            $post->created_at = Carbon::createFromFormat('Y-m-d\TH:i', $request->get('created_at'));
        }

        DB::transaction(function () use ($request, $post) {
            $post->save();
            $post->retag($request->get('tags'));
        });

        return new PostResource($post->load('tagged'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->load('tagged');

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => '',
            'body' => '',
            'is_public' => 'required|boolean',
            'created_at' => 'required|date_format:Y-m-d\TH:i',
            'tags' => 'array',
        ]);

        $post->fill($request->all());
        $post->created_at = Carbon::createFromFormat('Y-m-d\TH:i', $request->get('created_at'));

        DB::transaction(function () use ($request, $post) {
            $post->save();
            $post->retag($request->get('tags'));
        });

        return new PostResource($post->load('tagged'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return new PostResource($post);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function parse(Request $request)
    {
        return Post::parseMarkdown($request->get('text', ''));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Page;
use App\Http\Resources\Page as PageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Page::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->user()->pages();

        if ($request->filled('keyword')) {
            $query->searchBy($request->get('keyword'));
        }

        if ($request->filled('slug')) {
            $query->where('slug', $request->get('slug'));
        }

        if ($request->filled('exclude_id')) {
            $query->where('id', '!=', $request->get('exclude_id'));
        }

        if ($request->filled('sortBy')) {
            $direction = $request->get('descending') === 'true' ? 'desc' : 'asc';
            $query->orderBy($request->get('sortBy'), $direction);
        }
        $query->orderBy('id', 'desc');

        if ($request->filled('page')) {
            return PageResource::collection($query->paginate(12));
        }

        return PageResource::collection($query->get());
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
            'slug' => 'required|unique:pages',
            'is_public' => 'required|boolean',
            'created_at' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        $page = new Page($request->all());
        $page->user_id = $request->user()->id;
        if ($request->filled('created_at')) {
            $page->created_at = Carbon::createFromFormat('Y-m-d\TH:i', $request->get('created_at'));
        }

        DB::transaction(function() use ($page) {
            $page->save();
        });

        return new PageResource($page);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        return new PageResource($page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => '',
            'body' => '',
            'slug' => [
                'required',
                Rule::unique('pages')->ignore($page->id),
            ],
            'is_public' => 'required|boolean',
            'created_at' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $page->fill($request->all());
        $page->created_at = Carbon::createFromFormat('Y-m-d\TH:i', $request->get('created_at'));
        $page->save();

        return new PageResource($page);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return new PageResource($page);
    }
}

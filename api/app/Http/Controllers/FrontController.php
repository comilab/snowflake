<?php

namespace App\Http\Controllers;

use App\Page;
use App\Post;
use App\Setting;
use App\User;
use Conner\Tagging\Model\Tag;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();

        $query = Post::with(['tagged', 'user'])
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        $posts = $query->paginate($settings->per_page);

        return view('index', [
            'settings' => $settings,
            'query' => $query,
            'posts' => $posts,
        ]);
    }

    /**
     * @param Request $request
     * @param int $year
     * @param int $month
     * @param int $day
     * @return mixed
     */
    public function archives(Request $request, $year = null, $month = null, $day = null)
    {
        $settings = Setting::getAll();
        $query = Post::with(['tagged', 'user'])
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        $views = ['archives/index'];

        if ($year) {
            array_unshift($views, 'archives/year');
            array_unshift($views, "archives/{$year}");
            $query->whereYear('created_at', $year);
        }

        if ($month) {
            array_unshift($views, 'archives/month');
            array_unshift($views, "archives/{$year}_{$month}");
            $query->whereMonth('created_at', sprintf('%02d', $month));
        }

        if ($day) {
            array_unshift($views, 'archives/day');
            array_unshift($views, "archives/{$year}_{$month}_{$day}");
            $query->whereDay('created_at', sprintf('%02d', $day));
        }

        $posts = $query->paginate($settings->per_page);

        return view()->first($views, [
            'settings' => $settings,
            'query' => $query,
            'posts' => $posts,
            'year' => $year,
            'month' => $month,
            'day' => $day,
        ]);
    }

    public function tags(Request $request)
    {
        $tags = Tag::orderBy('count', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('tags/list', [
            'settings' => Setting::getAll(),
            'tags' => $tags,
        ]);
    }

    public function tagArchive(Request $request, $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $settings = Setting::getAll();

        $query = Post::with(['tagged', 'user'])
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->withAnyTag($slug);

        $posts = $query->paginate($settings->per_page);

        return view()->first(["tags/{$tag->slug}", 'tags/index'], [
            'settings' => $settings,
            'query' => $query,
            'posts' => $posts,
            'tag' => $slug,
        ]);
    }

    public function single(Request $request, $path)
    {
        if (strpos($path, 'posts/') !== false) {
            // post
            $id = str_replace('posts/', '', $path);
            $post = Post::with(['tagged', 'user'])
                ->where('is_public', true)
                ->findOrFail($id);

            return view()->first(['posts/index', "posts/{$id}"], [
                'settings' => Setting::getAll(),
                'post' => $post,
            ]);
        } else if (strpos($path, 'pages/') !== false) {
            // page
            $slug = str_replace('pages/', '', $path);
            $page = Page::with(['user'])
                ->where('slug', $slug)
                ->where('is_public', true)
                ->firstOrFail();

            return view()->first(['pages/index', "pages/{$slug}"], [
                'settings' => Setting::getAll(),
                'page' => $page,
            ]);
        } else {
            abort(404);
        }
    }

    public function rss()
    {
        $settings = Setting::getAll();

        $user = User::getData();

        $posts = Post::with(['tagged'])
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($settings->per_page);

        return response()
            ->view('rss', [
                'settings' => $settings,
                'user' => $user,
                'posts' => $posts,
            ])
            ->header('Content-Type', 'text/xml');
    }
}

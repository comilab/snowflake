@extends('layouts.app')

@section('content')
    <article>
        <header>
            <h2><a href="{{ $post->url }}">{{ $post->title }}</a></h2>
            <div class="border-top mt-1 pt-1 text-right">
                <time class="text-secondary" datetime="{{ $post->created_at->format('c') }}">{{ $post->created_at->format('Y/m/d H:i') }}</time>
            </div>
        </header>
        <main class="my-1">
            {!! $post->html_body !!}
        </main>
        <aside class="text-right">
            <ul class="list-inline">
                @foreach ($post->tags as $tag)
                    <li class="list-inline-item">
                        <i class="fa fa-tag text-secondary"></i>
                        <a href="/tags/{{ $tag }}">
                            {{ $tag }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>
    </article>
    <div class="row my-5">
        <div class="col-sm-6 text-truncate">
            @if ($post->prev)
                <a href="{{ $post->prev->url }}">
                    <i class="fa fa-angle-left mr-1"></i>
                    {{ $post->prev->title }}
                </a>
            @endif
        </div>
        <div class="col-sm-6 text-truncate text-right">
            @if ($post->next)
                <a href="{{ $post->next->url }}">
                    <i class="fa fa-angle-right fa-pull-right ml-2 mt-1"></i>
                    {{ $post->next->title }}
                </a>
            @endif
        </div>
    </div>
    <section>
        <h3>コメント</h3>
        @forelse ($post->comments as $comment)
            <div class="card my-4 p-3">
                <h5>{{ $comment->name }} さん</h5>
                <div class="border-top mt-1 mb-1 text-right">
                    <time class="text-secondary" datetime="{{ $comment->created_at->format('c') }}">{{ $comment->created_at->format('Y/m/d H:i') }}</time>
                </div>
                <div>{!! $comment->html_body !!}</div>
            </div>
        @empty
            <div class="alert alert-light">コメントはありません</div>
        @endforelse
        @include('partial.comment_form')
    </section>
@endsection

@extends('layouts.app')

@section('content')
    <article>
        <header>
            <h2><a href="{{ $page->url }}">{{ $page->title }}</a></h2>
            <div class="border-top mt-1 pt-1 text-right">
                <time class="text-secondary" datetime="{{ $page->created_at->format('c') }}">{{ $page->created_at->format('Y/m/d H:i') }}</time>
            </div>
        </header>
        <main class="my-1">
            {!! $page->html_body !!}
        </main>
    </article>
@endsection

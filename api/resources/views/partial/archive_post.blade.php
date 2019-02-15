<article class="my-5">
    <header>
        <h2><a href="{{ $post->url }}">{{ $post->title }}</a></h2>
        <div class="border-top mt-1 pt-1 text-right">
            <time class="text-secondary" datetime="{{ $post->created_at->format('c') }}">
                {{ $post->created_at->format('Y/m/d H:i') }}
            </time>
        </div>
    </header>
    <main class="my-2">
        {!! $post->split_html_body !!}
        @if($post->has_more)
            <a href="{{ $post->url }}" class="btn btn-outline-primary">続きを読む</a>
        @endif
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
        @include('partial.like', ['post' => $post])
    </aside>
</article>

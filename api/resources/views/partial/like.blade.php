@if ($settings->like_enabled)
    <form action="{{ url("posts/{$post->id}/likes") }}" method="post">
        {!! csrf_field() !!}
        @if (session("liked.{$post->id}"))
            <button type="submit" class="btn btn-primary btn-sm" disabled>
                <i class="fa fa-heart"></i>
                いいね！しました
            </button>
        @else
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="far fa-heart"></i>
                いいね！する
            </button>
        @endif
    </form>
@endif

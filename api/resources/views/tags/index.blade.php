@extends('layouts.app')

@section('content')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ url('tags') }}">タグ</a></li>
            <li class="breadcrumb-item active">{{ $tag }}</li>
        </ol>
    </nav>
    @forelse ($posts as $post)
        @include('partial.archive_post')
    @empty
        <div class="alert alert-warning">エントリがありません。</div>
    @endforelse

    <nav>
        {{ $posts->links() }}
    </nav>
@endsection

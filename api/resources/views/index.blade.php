@extends('layouts.app')

@section('content')
    @foreach ($posts as $post)
        @include('partial.archive_post')
    @endforeach

    <nav>
        {{ $posts->links() }}
    </nav>
@endsection

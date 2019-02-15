@extends('layouts.app')

@section('content')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">トップ</a></li>
            <li class="breadcrumb-item active">タグ</li>
        </ol>
    </nav>
    <ul>
        @foreach ($tags as $tag)
            <li>
                <a href="{{ url("tags/{$tag->slug}") }}">{{ $tag->name }}</a>
                ({{ $tag->count }})
            </li>
        @endforeach
    </ul>
@endsection

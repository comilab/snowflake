@extends('layouts.app')

@section('content')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ url('archives') }}">アーカイブ</a></li>
            @if ($year)
                @if ($month)
                    <li class="breadcrumb-item"><a href="{{ url("archives/{$year}") }}">{{ $year }}年</a></li>
                @else
                    <li class="breadcrumb-item active">{{ $year }}年</li>
                @endif
            @endif
            @if ($month)
                @if ($day)
                    <li class="breadcrumb-item"><a href="{{ url("archives/{$year}/{$month}") }}">{{ $month }}月</a></li>
                @else
                    <li class="breadcrumb-item active">{{ $month }}月</li>
                @endif
            @endif
            @if ($day)
                <li class="breadcrumb-item active">{{ $day }}日</li>
            @endif
        </ol>
    </nav>
    @forelse ($posts as $post)
        @include('partial.archive_post')
    @empty
        <div class="alert alert-warning">エントリがありません。</div>
    @endforelse

    <nav>
        @if ($posts->count())
            {{ $posts->links() }}
        @endif
    </nav>
@endsection

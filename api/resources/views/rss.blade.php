<feed xmlns="http://www.w3.org/2005/Atom">
    <title>{{ $settings->site_title }}</title>
    <link href="{{ url('rss') }}" rel="self" />
    <link href="{{ url('/') }}" />
    <updated>{{ $posts->first()->updated_at->format('c') }}</updated>
    <id>{{ url('/') }}</id>
    <author>
        <name>{{ $user->name }}</name>
    </author>
    <generator uri="">snowflake</generator>

    @foreach($posts as $post)
        <entry>
            <title>{{ $post->title }}</title>
            <link href="{{ $post->url }}" />
            <published>{{ $post->created_at->format('c') }}</published>
            <updated>{{ $post->updated_at->format('c') }}</updated>
            <content type="html">
                <![CDATA[{!! $post->html_body !!}]]>
            </content>
            <summary type="html">{{ $post->description }}</summary>
        </entry>
    @endforeach
</feed>

@if ($settings->comment_enabled)
    <form action="{{ url("posts/{$post->id}/comments") }}" method="post">
        {!! csrf_field() !!}
        <div class="form-group row">
            <label for="comment_name" class="col-sm-2 col-form-label">名前</label>
            <div class="col-sm-10">
                <input type="text" id="comment_name" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="comment_body" class="col-sm-2 col-form-label">コメント</label>
            <div class="col-sm-10">
                <textarea id="comment_body" name="body" class="form-control" rows="3" required>{{ old('body') }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">送信</button>
            </div>
        </div>
    </form>
@endif

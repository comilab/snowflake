<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['name', 'body'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return string
     */
    public function getHtmlBodyAttribute()
    {
        return nl2br(htmlspecialchars($this->body));
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Searchable;

    protected $fillable = ['title', 'body', 'slug', 'is_public'];

    protected $casts = [
        'user_id' => 'integer',
        'is_public' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    public function getDescriptionAttribute()
    {
        return mb_substr($this->body, 0, 200, 'UTF-8');
    }

    /**
     * @return string
     */
    public function getHtmlBodyAttribute()
    {
        return Post::parseMarkdown($this->body);
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return url("pages/{$this->slug}");
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @param string $boolean
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSingleSearchBy($query, $keyword, $boolean = 'and')
    {
        return $query->where(function($query) use ($keyword) {
            $query
                ->orWhere('title', 'LIKE', "%{$keyword}%")
                ->orWhere('body', 'LIKE', "%{$keyword}%");
        }, null, null, $boolean);
    }
}

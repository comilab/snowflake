<?php

namespace App;

use cebe\markdown\GithubMarkdown;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \Conner\Tagging\Taggable, Searchable;

    protected $fillable = ['title', 'body', 'is_public'];

    protected $casts = [
        'user_id' => 'integer',
        'is_public' => 'boolean',
    ];

    /**
     * @var Post
     */
    protected $prev;

    /**
     * @var Post
     */
    protected $next;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return string[]
     */
    public function getTagsAttribute()
    {
        return $this->tagged->pluck('tag_name');
    }

    /**
     * @return string
     */
    public function getDescriptionAttribute()
    {
        return mb_substr($this->split_body, 0, 200, 'UTF-8');
    }

    /**
     * @return string
     */
    public function getSplitBodyAttribute()
    {
        return explode('<!-- more -->', $this->body)[0];
    }

    /**
     * @return string
     */
    public function getSplitHtmlBodyAttribute()
    {
        return $this->parseMarkdown($this->split_body);
    }

    /**
     * @return string
     */
    public function getHtmlBodyAttribute()
    {
        return $this->parseMarkdown($this->body);
    }

    /**
     * @return bool
     */
    public function getHasMoreAttribute()
    {
        return strpos($this->body, '<!-- more -->') !== false;
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return url("posts/{$this->id}");
    }

    /**
     * @return \App\Post|null
     */
    public function getPrevAttribute()
    {
        if (!$this->prev) {
            $this->prev = $this->getSibling(true);
        }
        return $this->prev;
    }

    /**
     * @return \App\Post|null
     */
    public function getNextAttribute()
    {
        if (!$this->next) {
            $this->next = $this->getSibling(false);
        }
        return $this->next;
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

    /**
     * @param string $text
     * @return string
     */
    public static function parseMarkdown($text)
    {
        $parser = new GithubMarkdown();
        $parser->html5 = true;
        $parser->enableNewlines = true;

        return $parser->parse($text);
    }

    /**
     * @param bool $prev
     * @return \App\Post|null
     */
    protected function getSibling($prev = true)
    {
        $settings = Setting::getAll();
        $direction = 'desc';
        if (!$prev) {
            $direction = 'asc';
        }
        $comparison = $direction === 'asc' ? '>=' : '<=';

        $self = null;
        $sibling = null;

        self::orderBy('created_at', $direction)
            ->orderBy('id', $direction)
            ->where('created_at', $comparison, $this->created_at)
            ->chunk(100, function($posts) use (&$self, &$sibling) {
                foreach ($posts as $post) {
                    if ($self) {
                        $sibling = $post;
                        return false;
                    }
                    if ($post->id === $this->id) {
                        $self = $post;
                    }
                }
            });

        return $sibling;
    }
}

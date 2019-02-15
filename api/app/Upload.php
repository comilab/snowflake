<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use Searchable;

    protected $fillable = ['name'];

    protected $appends = ['public_path', 'public_thumb_path'];

    protected $casts = [
        'weight' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return string
     */
    public function getPathAttribute()
    {
        return storage_path("app/public/uploads/{$this->filename}");
    }

    /**
     * @return string
     */
    public function getThumbPathAttribute()
    {
        return storage_path("app/public/uploads/thumb/{$this->filename}");
    }

    /**
     * @return string
     */
    public function getPublicPathAttribute()
    {
        return "/storage/uploads/{$this->filename}";
    }

    /**
     * @return string
     */
    public function getPublicThumbPathAttribute()
    {
        if (!$this->isImage()) {
            return null;
        }

        return "/storage/uploads/thumb/{$this->filename}";
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @param string $boolean
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSingleSearchBy($query, $keyword, $boolean = 'and')
    {
        return $query->where('name', 'LIKE', "%{$keyword}%");
    }

    /**
     * @return bool
     */
    public function isImage()
    {
        return strpos($this->filetype, 'image/') === 0;
    }
}

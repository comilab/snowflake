<?php

namespace App;

/**
 * Class Searchable
 * @package App
 *
 * @method static static singleSearchBy(string $keyword, string $boolean = 'and')
 * @method static static searchBy(string $keyword, string $boolean = 'and')
 */
trait Searchable
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @param string $boolean
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function scopeSingleSearchBy($query, $keyword, $boolean = 'and');

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @param string $boolean
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchBy($query, $keyword, $boolean = 'and')
    {
        if (!$keyword) {
            return $query;
        }

        $keywords = $this->sanitizeKeywords($keyword);
        return $query->where(function($query) use ($keywords, $boolean) {
            foreach ($keywords as $keyword) {
                $query->singleSearchBy($keyword, $boolean);
            }
        });
    }

    /**
     * @param string $keywords
     * @return \Illuminate\Support\Collection
     */
    protected function sanitizeKeywords($keywords)
    {
        if (is_string($keywords)) {
            $keywords = explode(' ', mb_convert_kana($keywords, 's', 'UTF-8'));
        }

        return collect($keywords);
    }
}

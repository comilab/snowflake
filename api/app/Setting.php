<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $casts = [
        'data' => 'object',
    ];

    protected static $data;

    protected static function boot()
    {
        parent::boot();

        self::saved(function() {
            self::$data = null;
        });
    }

    /**
     * @return object
     */
    public static function getAll()
    {
        if (!self::$data) {
            self::$data = self::first()->data;
        }
        return self::$data;
    }
}

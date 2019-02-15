<?php

use Faker\Generator as Faker;

$factory->define(App\Setting::class, function (Faker $faker) {
    return [
        'data' => [
            'site_title' => 'snowflake',
            'comment_enabled' => true,
            'like_enabled' => true,
            'per_page' => 10,
        ],
    ];
});

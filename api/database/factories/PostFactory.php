<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomDigit(),
        'title' => $faker->sentence(),
        'body' => $faker->realText(),
    ];
});

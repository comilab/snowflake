<?php

use Faker\Generator as Faker;

$factory->define(App\Page::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomDigit(),
        'title' => $faker->sentence(),
        'body' => $faker->realText(),
        'slug' => $faker->unique()->asciify('********'),
    ];
});

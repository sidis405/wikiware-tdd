<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $title = $faker->sentence,
        'slug' => str_slug($title),
        'user_id' => factory(App\User::class),
        'preview' => $faker->sentence,
        'created_at' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now')
    ];
});

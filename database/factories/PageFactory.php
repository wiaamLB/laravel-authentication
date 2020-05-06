<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Page;
use Faker\Generator as Faker;

$factory->define(Page::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'alias' => $faker->word,
        'active' => $faker->boolean,
        'page_title' => $faker->title,
        'description' => $faker->sentence,
        'content' => $faker->sentence,
    ];
});

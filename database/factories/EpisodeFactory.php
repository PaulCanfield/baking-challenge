<?php

use Faker\Generator as Faker;
use App\Season;

$factory->define(App\Episode::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'season_id' => factory(Season::class)
    ];
});

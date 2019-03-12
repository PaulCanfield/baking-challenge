<?php

use Faker\Generator as Faker;
use App\Season;

$factory->define(App\Episode::class, function (Faker $faker) {
    return [
        'episode' => $faker->numberBetween(1,12),
        'title' => $faker->sentence,
        'season_id' => factory(Season::class)
    ];
});

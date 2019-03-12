<?php

use Faker\Generator as Faker;

$factory->define(App\Baker::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'season_id' => factory('App\Season')
    ];
});

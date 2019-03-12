<?php

use Faker\Generator as Faker;

$factory->define(App\Season::class, function (Faker $faker) {
    return [
        'season'   => $faker->numberBetween(1900,(int) date('Y')),
        'owner_id' => factory(App\User::class),
        'title'    => $faker->sentence,
        'note'     => $faker->paragraph
    ];
});

<?php

use Faker\Generator as Faker;

$factory->define(App\Result::class, function (Faker $faker) {
    return [
        'result'     => implode(' ', $faker->words(2)),
        'eliminated' => false
    ];
});

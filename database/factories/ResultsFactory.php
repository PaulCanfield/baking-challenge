<?php

use Faker\Generator as Faker;

$factory->define(App\Result::class, function (Faker $faker) {
    return [
        'result'     => implode(' ', $faker->words(2)),
        'eliminated' => false
    ];
});

$factory->state(App\Result::class, 'eliminated', [
    'result'      => 'Eliminated',
    'key'         => 'eliminated',
    'eliminated'  => true
]);

$factory->state(App\Result::class, 'star baker', [
    'result'      => 'Star Baker',
    'key'         => 'star_baker',
    'eliminated'  => false
]);
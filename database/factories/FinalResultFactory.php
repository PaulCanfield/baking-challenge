<?php

use Faker\Generator as Faker;

$factory->define(App\FinalResult::class, function (Faker $faker) {
    return [
        'season_id' => factory(App\Season::class),
        'baker_id'  => function (array $finalResult) {
            return factory(App\Baker::class)->create(
                [ 'season_id' => $finalResult['season_id'] ]
            )->id;
        },
        'owner_id'  => function (array $finalResult) {
            return App\Season::find($finalResult['season_id'])->allMembers->first()->id;
        },
        'winner' => false,
    ];
});

$factory->state(App\FinalResult::class, 'winner', [ 'winner' => true ]);

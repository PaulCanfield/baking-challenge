<?php

use Faker\Generator as Faker;
use App\Season;

$factory->define(App\Episode::class, function (Faker $faker) {
    return [
        'title'     => $faker->sentence,
        'season_id' => factory(Season::class)
    ];
});

$factory->state(App\Episode::class, 'hasResults', [ ]);
$factory->state(App\Episode::class, 'finalized',  [ ]);

$factory->afterCreatingState(App\Episode::class, 'hasResults', function ($episode, $faker) {
    $starBaker = App\Result::where('result', '=', 'Star Baker')->get()->first();
    if (!$starBaker) {
        $starBaker = factory(App\Result::class)->state('star baker')->create();
    }

    $eliminated = App\Result::where('result', '=', 'Eliminated')->get()->first();
    if (!$eliminated) {
        $eliminated = factory(App\Result::class)->state('eliminated')->create();
    }

    $bakers = factory(App\Baker::class, 2)->create([ 'season_id' => $episode->season->id ]);

    factory(App\EpisodeResults::class)->create([
        'episode_id'   => $episode->id,
        'baker_id'     => $bakers->get(0)->id,
        'result_id'    => $starBaker->id
    ]);

    factory(App\EpisodeResults::class)->create([
        'episode_id'   => $episode->id,
        'baker_id'     => $bakers->get(1)->id,
        'result_id'    => $eliminated->id
    ]);
});

$factory->afterCreatingState(App\Episode::class, 'finalized', function ($episode, $faker) {
    $episode->finalize();
});

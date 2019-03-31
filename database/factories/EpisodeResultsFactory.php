<?php

use Faker\Generator as Faker;
use App\EpisodeResults;
use Facades\Tests\Setup\SeasonFactory;
use App\Episode;
use App\Season;
use App\Baker;
use App\Result;

$factory->define(EpisodeResults::class, function (Faker $faker) {
    return [
        'result_id'  => factory(Result::class)->create()->id,
        'notes'      => $faker->paragraph
    ];
});

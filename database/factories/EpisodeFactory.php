<?php

namespace Database\Factories;

use App\Episode;
use App\Season;
use App\Result;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\EpisodeResult;
use App\Baker;
use Illuminate\Support\Str;

class EpisodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Episode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'     => $this->faker->sentence,
            'season_id' => Season::factory()->create()
        ];
    }

    public function hasResults() {
        return $this->afterCreating(function (Episode $episode) {
            $starBaker = Result::where('result', '=', 'Star Baker')->get()->first();

            if (!$starBaker) {
                $starBaker = Result::factory()->starBaker()->create();
            }

            $eliminated = Result::where('result', '=', 'Eliminated')->get()->first();
            if (!$eliminated) {
                $eliminated = Result::factory()->eliminated()->create();
            }

            $bakers = Baker::factory()->count(2)->create([ 'season_id' => $episode->season->id ]);

            EpisodeResult::factory()->create([
                'episode_id'   => $episode->id,
                'baker_id'     => $bakers->get(0)->id,
                'result_id'    => $starBaker->id
            ]);

            EpisodeResult::factory()->create([
                'episode_id'   => $episode->id,
                'baker_id'     => $bakers->get(1)->id,
                'result_id'    => $eliminated->id
            ]);

        });
    }

    public function finalized() {
        return $this->afterCreating(function(Episode $episode) {
            $episode->finalize();
        });
    }
}

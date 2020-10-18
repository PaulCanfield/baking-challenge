<?php
namespace Database\Factories;

use App\FinalResult;
use App\Season;
use App\Baker;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinalResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FinalResult::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'season_id' => Season::factory()->create(),
            'baker_id'  => function (array $finalResult) {
                return Baker::factory()->create(
                    [ 'season_id' => $finalResult['season_id'] ]
                )->id;
            },
            'owner_id'  => function (array $finalResult) {
                return Season::find($finalResult['season_id'])->allMembers->first()->id;
            },
            'winner' => false,
        ];
    }

    public function winner() {
        return $this->state([
            'winner' => true
        ]);
    }
}

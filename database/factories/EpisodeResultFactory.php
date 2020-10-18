<?php

namespace Database\Factories;

use App\Models\EpisodeResult;
use App\Models\Result;
use Illuminate\Database\Eloquent\Factories\Factory;

class EpisodeResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EpisodeResult::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'result_id'  => Result::factory(),
            'notes'      => $this->faker->paragraph
        ];
    }
}

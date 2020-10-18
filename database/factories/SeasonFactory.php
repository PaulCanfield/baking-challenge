<?php
namespace Database\Factories;

use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class SeasonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Season::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'year'     => $this->faker->numberBetween(1900,(int) date('Y')),
            'owner_id' => User::factory()->create(),
            'title'    => $this->faker->sentence,
            'note'     => $this->faker->paragraph
        ];
    }
}

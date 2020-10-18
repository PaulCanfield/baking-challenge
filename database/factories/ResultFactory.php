<?php
namespace Database\Factories;

use App\Models\Result;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Result::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'result'     => implode(' ', $this->faker->words(2)),
            'eliminated' => false
        ];
    }

    public function eliminated() {
        return $this->state([
            'result'      => 'Eliminated',
            'key'         => 'eliminated',
            'eliminated'  => true
        ]);
    }

    public function starBaker() {
        return $this->state([
            'result'      => 'Star Baker',
            'key'         => 'star_baker',
            'eliminated'  => false
        ]);
    }
}

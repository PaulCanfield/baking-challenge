<?php

namespace Database\Seeders;

use App\Models\Season;
use App\Models\Episode;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Seeder;

class EpisodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (array_search(App::environment(), ['prod', 'production']) === false) {
            $season = Season::firstWhere('title', 'The Great Alethgar Bake Off');

            Episode::factory([
                'title' => 'Week One: Men\'s food',
                'season_id' => $season->id
            ])->create();

            Episode::factory([
                'title' => 'Week Two: Womans\'s food',
                'season_id' => $season->id
            ])->create();

            Episode::factory([
                'title' => 'Week Three: Infused Wines Week',
                'season_id' => $season->id
            ])->create();

            Episode::factory([
                'title' => 'Week Four: Food from the War Camps',
                'season_id' => $season->id
            ]);

            Episode::factory([
                'title' => 'Week Five: Valhav Pastries',
                'season_id' => $season->id
            ]);

            Episode::factory([
                'title' => 'Week Six: Fried food',
                'season_id' => $season->id
            ]);

            Episode::factory([
                'title' => 'Week Seven: Cooking Spren',
                'season_id' => $season->id
            ]);

            Episode::factory([
                'title' => 'Week Eight: Deserts',
                'season_id' => $season->id
            ]);

            Episode::factory([
                'title' => 'Week Nine: Semi-finals Parshendi Breads',
                'season_id' => $season->id
            ]);

            Episode::factory([
                'title' => 'Week Ten: Finals',
                'season_id' => $season->id
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Seeder;
use App\Models\Season;
use App\Models\Baker;

class BakersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (array_search(App::environment(), ['prod', 'production']) === false) {
            $season = Season::firstWhere([
                'title' => 'The Great Alethgar Bake Off'
            ]);

            Baker::factory()->create([
                'name'      => 'Dalinar Kholin',
                'season_id' => $season->id
            ]);

            Baker::factory()->create([
                'name'      => 'Jasnah Kholin',
                'season_id' => $season->id
            ]);

            Baker::factory()->create([
                'name'      => 'Adolin Kholin',
                'season_id' => $season->id
            ]);

            Baker::factory()->create([
                'name'      => 'Torol Sadeas',
                'season_id' => $season->id
            ]);

            Baker::factory()->create([
                'name'      => 'Kaladin Stormblessed',
                'season_id' => $season->id
            ]);

            Baker::factory()->create([
                'name'      => 'Meridas Amaram',
                'season_id' => $season->id
            ]);

            Baker::factory()->create([
                'name'      => 'Szeth-son-son-Vallano',
                'season_id' => $season->id
            ]);

            Baker::factory()->create([
                'name'      => 'Shallan Davar',
                'season_id' => $season->id
            ]);

            Baker::factory()->create([
                'name'      => 'Taravangian',
                'season_id' => $season->id
            ]);

            Baker::factory()->create([
                'name'      => 'Lopan',
                'season_id' => $season->id
            ]);

            Baker::factory()->create([
                'name'      => 'Navani Kholin',
                'season_id' => $season->id
            ]);

            Baker::factory()->create([
                'name'      => 'Rock',
                'season_id' => $season->id
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Seeder;
use App\Models\Season;

class SeasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (array_search(App::environment(), ['prod', 'production']) === false) {
            Season::factory()->create([
                'year'     => (int) date('Y'),
                'owner_id' => User::firstWhere(['email' => 'admin@example.com'])->id,
                'title'    => 'The Great Alethgar Bake Off',
                'note'     => 'The finest bakers square off on the shattered plains'
            ]);
        }
    }
}

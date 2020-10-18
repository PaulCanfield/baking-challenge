<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (array_search(App::environment(), ['prod', 'production']) === false) {
            User::factory()->create([
                'name' => 'Dalenar Kholin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('admin1234'), // password
                'remember_token' => Str::random(10)
            ]);
        }
    }
}

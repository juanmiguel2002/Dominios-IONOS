<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Juanmi',
            'email' => 'web@ivarscomagenciadepublicidad.com',
            'password' => bcrypt('iI8YNi506a@@'),
        ]);
        User::factory()->create([
            'name' => 'Jose',
            'email' => 'joseivars@ivarscom.com',
            'password' => bcrypt('L76tR+9n62/D'),
        ]);
    }
}

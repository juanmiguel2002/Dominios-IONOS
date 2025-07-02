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
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
        ]);
        User::factory()->create([
            'name' => 'Jose',
            'email' => 'email@email.com',
            'password' => bcrypt(''),
        ]);
    }
}

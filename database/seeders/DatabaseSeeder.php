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

        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@example.com',
        //     'email' => 'admin@example.com',
        // ]);

        $this->call([
            MenuSeeder::class,
            MenuSequenceSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            DistrictSeeder::class,
            CourtSeeder::class,
        ]);
    }
}

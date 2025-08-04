<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => 'admin123'
        ]);
        $admin->assignRole('admin');

        $advocates = \App\Models\User::factory(5)->create();
        foreach ($advocates as $advocate) {
            $advocate->assignRole('advocate');
        }

    }
}

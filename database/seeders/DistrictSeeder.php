<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            ['name' => 'South', 'slug' => 'south'],
            ['name' => 'East', 'slug' => 'east'],
            ['name' => 'West', 'slug' => 'west'],
            ['name' => 'Central', 'slug' => 'central'],
            ['name' => 'Malir', 'slug' => 'malir'],
            ['name' => 'Special Courts', 'slug' =>  'courts'],
            ['name' => 'Arbitration', 'slug' => 'arbitration'],
        ];

        foreach ($districts as $district) {
            District::create($district);
        }
    }
}

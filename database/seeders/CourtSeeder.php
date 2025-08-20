<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Court;
use App\Models\District;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courts = [
            // SOUTH
            [ 'district_id' => 'South', 'name' => 'IIND ADJ South'],
            [ 'district_id' => 'South', 'name' => 'XIIITH SCJ South'],
            [ 'district_id' => 'South', 'name' => 'IIND SCJ South'],
            [ 'district_id' => 'South', 'name' => 'VITH SCJ South'],
            [ 'district_id' => 'South', 'name' => 'XTH SCJ South'],
            [ 'district_id' => 'South', 'name' => 'XIITH SCJ South'],
            [ 'district_id' => 'South', 'name' => 'XIVTH SCJ South'],
            [ 'district_id' => 'South', 'name' => 'XIVTH Civil Judge South'],

            // EAST
            [  'district_id' => 'East', 'name' => 'IST SCJ East'],
            [  'district_id' => 'East', 'name' => 'IVTH SCJ East'],
            [  'district_id' => 'East', 'name' => 'VIII TH SCJ East'],

            // WEST
            [ 'district_id' => "West", 'name' => 'IIIRD SCJ West'],
            [ 'district_id' => "West", 'name' => 'VITH SCJ West'],
            [ 'district_id' => "West", 'name' => 'XVIIITH SCJ West'],

            // CENTRAL
            [ 'district_id' => "Central", 'name' => 'VTH SCJ Central'],

            // MALIR
            ['district_id' => "Malir", 'name' => 'IST SCJ Malir'],
            ['district_id' => "Malir", 'name' => 'IIND SCJ Malir'],
            ['district_id' => "Malir", 'name' => 'IIIRD SCJ Malir'],
            ['district_id' => "Malir", 'name' => 'VTH SCJ Malir'],
            ['district_id' => "Malir", 'name' => 'IIIRD ADJ Malir'],

            // SPECIAL COURTS
            [ 'district_id' => "Special Courts", 'name' => 'Custom Court-II, S. No.11'],
            [ 'district_id' => "Special Courts", 'name' => 'Spl. Case'],
            [ 'district_id' => "Special Courts", 'name' => 'Arbitration'],

        ];

        foreach ($courts as $court) {
            $district = District::where('name',$court['district_id'])->first();
            if(!District::where('name',$court['district_id'])->exists()){
                $district = District::first();
            }
            
            $court['district_id'] = $district->id;
            Court::create($court);
        }
    }
}

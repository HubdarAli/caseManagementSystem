<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Court>
 */
class CourtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->streetName . ' Court',
            'type' => $this->faker->randomElement(['Civil', 'Criminal', 'Family']),
            'district_id' => \App\Models\District::inRandomOrder()->first()->id,
        ];
    }
}

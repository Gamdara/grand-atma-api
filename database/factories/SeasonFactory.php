<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Season>
 */
class SeasonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            //
            'discount_type' => fake()->randomElement(['flat','percentage']),
            'start_date' => fake()->dateTimeBetween('-3 months','now'),
            'end_date' => fake()->dateTimeBetween('now','3 months'),
        ];
    }
}

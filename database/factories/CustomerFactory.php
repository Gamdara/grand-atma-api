<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
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
            'no_identity' => fake()->randomNumber(9),
            'institution' => fake()->company(),
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'created_at' => fake()->dateTimeThisYear(),
        ];
    }
}

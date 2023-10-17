<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['flat','percentage']);
        // dd($type == 'flat' ? 100000 : 5 );
        return [
            'code' => Str::upper(Str::random(10)),
            'start_date' => fake()->dateTimeBetween('-3 months','now'),
            'end_date' => fake()->dateTimeBetween('now','3 months'),
            'discount_type' => $type,
            'discount_amount' =>  intval(fake()->numberBetween(2,10)) * intval($type == 'flat' ? 100000 : 5) ,
            'is_valid' => 1
        ];
    }
}

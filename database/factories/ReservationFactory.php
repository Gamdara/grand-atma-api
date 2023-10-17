<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    private static $number = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeThisYear()->format('Y-m-d');
        $lama = fake()->numberBetween(1,7);
        return [
            //
            'coupon_id' => fake()->boolean() ? null : Coupon::where('is_valid', 1)->first()?->coupon_id,
            'customer_id' => Customer::all()->random(1)->first()->customer_id,
            'booking_id' => 'P'.date('dmy').'-'.self::$number++,
            'type' => 'personal',
            'adults' => fake()->numberBetween(1,9),
            'kids' => fake()->numberBetween(1,9),
            'status' => 'pending',
            'start_date' => $date,
            'end_date' => Carbon::createFromFormat('Y-m-d', $date)->addDays($lama),
            'request' => '',
        ];
    }

    public function group(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'group',
                'pic' => User::whereHas('role', function ($query) {
                    return $query->where('name', '=', 'sales-marketing');
                })->first()->user_id,
            ];
        });
    }
}

<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
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
            'created_at' => fake()->dateTimeThisYear(),
            'type' => 'bail',
            'amount' => function ($attributes){
                $res = Reservation::find($attributes['reservation_id']);
                return $res->type == 'personal' ? $res->total : $res->total / 2;
            }
        ];
    }

    public function lunas(): Factory
    {
        return $this->state(function (array $attributes) {
            Reservation::find($attributes['reservation_id'])->update(['user_id' => User::whereHas('role', function ($query) {
                return $query->where('name', '=', 'front-office');
            })->first()->user_id]);
            return [
                'created_at' => fake()->dateTimeThisYear(),
                'type' => 'settle',
                'no_invoice' => 'P'.date('dmy').'-'.$attributes['reservation_id'],
                'amount' => function ($attributes){
                    $res = Reservation::find($attributes['reservation_id']);
                    $sisa = $res->total - $res->transaction->where('type','bail')->first()->amount;
                    return $sisa;
                }
            ];
        });
    }
}

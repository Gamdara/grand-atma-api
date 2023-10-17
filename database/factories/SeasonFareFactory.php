<?php

namespace Database\Factories;

use App\Models\RoomType;
use App\Models\Season;
use App\Models\SeasonFare;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SeasonFare>
 */
class SeasonFareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // dd(RoomType::all()->pluck('room_type_id'));
        $id = fake()->unique(true)->randomElement(RoomType::all()->pluck('room_type_id'));
        // var_dump($id);
        return [
            //
            'discount_amount' => function (array $attributes) {
                // dd($attributes);
                return intval(fake()->numberBetween(2,10)) * intval(Season::find($attributes['season_id'])->discount_type == 'flat' ? 100000 : 5) ;
            },
            'room_type_id' => $id
        ];
    }
}

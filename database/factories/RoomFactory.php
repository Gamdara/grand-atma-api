<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    private static $number = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // dd(true);
        // dd(App\Models\RoomType::class);
        // $type = RoomType::all()->random(1)->first();
        return [
            //
            // 'room_type_id'   => $type->room_type_id,
            'number'   => self::$number++,
            'capacity'   => fake()->numberBetween(1,4),
            // 'bed_type'   => fake()->randomElement(explode(',', $type->bed_options)) ,
            'is_smoking' => fake()->boolean()

        ];
    }
}

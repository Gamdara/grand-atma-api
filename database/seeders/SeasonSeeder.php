<?php

namespace Database\Seeders;

use App\Models\RoomType;
use App\Models\Season;
use App\Models\SeasonFare;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Log;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        //

        Season::factory()->count(5)
        ->sequence(
            ['name' => 'Musim Panas'],
            ['name' => 'Musim Hujan'],
            ['name' => 'Musim Kemarau'],
            ['name' => 'Musim Jeruk'],
            ['name' => 'Natal dan Tahun Baru'],
        )
        ->create()
        ->each(function (Season $season) {
            $types = RoomType::all()->random(3)->mapWithKeys(function ($type) use($season) {
                return [$type->room_type_id => [ 'discount_amount' => intval(fake()->numberBetween(2,10)) * intval($season->discount_type == 'flat' ? 100000 : 5) ]];
            });
            $season->roomType()->attach($types);
        });
    }
}

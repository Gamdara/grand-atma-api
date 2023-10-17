<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomTypeFactory extends Factory
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
            'details' => '
            AC
            Air minum kemasan gratis
            Brankas dalam kamar (ukuran laptop)
            Fasilitas membuat kopi/teh
            Jubah mandi
            Layanan kamar (24 jam)
            Meja tulis
            Minibar
            Pembersihan kamar harian
            Pengering rambut
            Peralatan mandi gratis
            Sandal
            Telepon
            Tempat tidur ekstra (biaya tambahan)
            Tempat tidur premium
            Tirai kedap-cahaya
            TV kabel
            TV LCD
            Wi-Fi gratis
            '
        ];
    }
}

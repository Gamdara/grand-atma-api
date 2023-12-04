<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RoomType::factory()->count(4)->sequence(
            [
                'name' => 'SUPERIOR',
                'img' => 'https://www.imgacademy.com/sites/default/files/legacy-hotel-rendering-guest-room.jpg',
                'fare' => '500000',
                'bed_options' => 'double,twin',
                'description' =>
'22 meter persegi.
|Internet - WiFi Gratis
|Hiburan - Televisi LCD dengan channel TV premium channels
|Makan Minum - Pembuat kopi/teh, minibar
|Untuk tidur - Seprai kualitas premium dan gorden/tirai kedap cahaya
|Kamar Mandi - Kamar mandi pribadi dengan shower, jubah mandi, dan sandal
|Kemudahan - Brankas (muat laptop), Meja tulis, dan Telepon
|Kenyamanan - AC dan layanan pembenahan kamar harian
|Merokok/Dilarang Merokok'
            ],
            [
                'name' => 'DOUBLE DELUXE',
                'img' => 'https://www.imgacademy.com/sites/default/files/legacy-hotel-rendering-guest-room.jpg',
                'fare' => '650000',
                'bed_options' => 'double,twin',
                'description' =>
'24 meter persegi.
|Internet - WiFi Gratis
|Hiburan - Televisi LCD dengan channel TV premium channels
|Makan Minum - Pembuat kopi/teh, minibar
|Untuk tidur - Seprai kualitas premium dan gorden/tirai kedap cahaya
|Kamar Mandi - Kamar mandi pribadi dengan shower, jubah mandi, dan sandal
|Kemudahan - Brankas (muat laptop), Meja tulis, dan Telepon
|Kenyamanan - AC dan layanan pembenahan kamar harian
|Merokok/Dilarang Merokok'
            ],
            [
                'name' => 'EXECUTIVE DELUXE',
                'img' => 'https://www.imgacademy.com/sites/default/files/legacy-hotel-rendering-guest-room.jpg',
                'fare' => '900000',
                'bed_options' => 'king',
                'description' =>
'Kamar berukuran 36 meter persegi, menampilkan pemandangan kota.
|Internet - WiFi Gratis
|Hiburan - Televisi LCD dengan channel TV premium channels
|Makan Minum - Pembuat kopi/teh, minibar, layanan kamar 24-jam, air minum kemasan gratis,
termasuk sarapan
|Untuk tidur - Seprai kualitas premium dan gorden/tirai kedap cahaya
|Kamar Mandi - Kamar mandi pribadi dengan shower, jubah mandi, dan sandal
|Kemudahan - Brankas (muat laptop), Meja tulis, dan Telepon
|Kenyamanan - AC dan layanan pembenahan kamar harian
|Merokok/Dilarang Merokok'
            ],
            [
                'name' => 'JUNIOR SUITE',
                'img' => 'https://www.imgacademy.com/sites/default/files/legacy-hotel-rendering-guest-room.jpg',
                'fare' => '1050000',
                'bed_options' => 'king',
                'description' =>
'Kamar berukuran 46 meter persegi, menampilkan pemandangan kota.
|Layout - ruang duduk terpisah
|Internet - WiFi Gratis
|Hiburan - Televisi LCD dengan channel TV premium channels
|Makan Minum - Pembuat kopi/teh, minibar
|Untuk tidur - Seprai kualitas premium dan gorden/tirai kedap cahaya
|Kamar Mandi - Kamar mandi pribadi dengan shower, jubah mandi, dan sandal
|Kemudahan - Brankas (muat laptop), Meja tulis, dan Telepon
|Kenyamanan - AC dan layanan pembenahan kamar harian
|Merokok/Dilarang Merokok'
            ]
        )
        ->has(
            Room::factory()->count(30)->state(function (array $attributes, RoomType $type) {
                return ['bed_type' => fake()->randomElement(explode(',',$type->bed_options)) ];
            })
        )
        ->create();
    }
}

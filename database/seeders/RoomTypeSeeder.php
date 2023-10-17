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
                'bed_options' => '1 double,1 twin',
                'description' => '
                22 meter persegi

                Internet - WiFi Gratis
                Hiburan - Televisi LCD dengan channel TV premium channels
                Makan Minum - Pembuat kopi/teh, minibar, layanan kamar 24-jam, air minum kemasan gratis,
                termasuk sarapan
                Untuk tidur - Seprai kualitas premium dan gorden/tirai kedap cahaya
                Kamar Mandi - Kamar mandi pribadi dengan shower, jubah mandi, dan sandal
                Kemudahan - Brankas (muat laptop), Meja tulis, dan Telepon; tempat tidur lipat/tambahan tersedia
                berdasarkan permintaan
                Kenyamanan - AC dan layanan pembenahan kamar harian
                Merokok/Dilarang Merokok
                '
            ],
            [
                'name' => 'DOUBLE DELUXE',
                'img' => 'https://www.imgacademy.com/sites/default/files/legacy-hotel-rendering-guest-room.jpg',
                'fare' => '650000',
                'bed_options' => '1 double,2 twin',
                'description' => '
                24 meter persegi

                Internet - WiFi Gratis
                Hiburan - Televisi LCD dengan channel TV premium channels
                Makan Minum - Pembuat kopi/teh, minibar, layanan kamar 24-jam, air minum kemasan gratis,
                termasuk sarapan
                Untuk tidur - Seprai kualitas premium dan gorden/tirai kedap cahaya
                Kamar Mandi - Kamar mandi pribadi dengan shower, jubah mandi, dan sandal
                Kemudahan - Brankas (muat laptop), Meja tulis, dan Telepon; tempat tidur lipat/tambahan tersedia
                berdasarkan permintaan
                Kenyamanan - AC dan layanan pembenahan kamar harian
                Merokok/Dilarang Merokok
                '
            ],
            [
                'name' => 'EXECUTIVE DELUXE',
                'img' => 'https://www.imgacademy.com/sites/default/files/legacy-hotel-rendering-guest-room.jpg',
                'fare' => '900000',
                'bed_options' => '1 king',
                'description' => '
                Kamar berukuran 36 meter persegi, menampilkan pemandangan kota

                Internet - WiFi Gratis
                Hiburan - Televisi LCD dengan channel TV premium channels
                Makan Minum - Pembuat kopi/teh, minibar, layanan kamar 24-jam, air minum kemasan gratis,
                termasuk sarapan
                Untuk tidur - Seprai kualitas premium dan gorden/tirai kedap cahaya
                Kamar Mandi - Kamar mandi pribadi dengan shower, jubah mandi, dan sandal
                Kemudahan - Brankas (muat laptop), Meja tulis, dan Telepon; tempat tidur lipat/tambahan tersedia
                berdasarkan permintaan
                Kenyamanan - AC dan layanan pembenahan kamar harian
                Merokok/Dilarang Merokok
                '
            ],
            [
                'name' => 'JUNIOR SUITE',
                'img' => 'https://www.imgacademy.com/sites/default/files/legacy-hotel-rendering-guest-room.jpg',
                'fare' => '1050000',
                'bed_options' => '1 king',
                'description' => '
                Kamar berukuran 46 meter persegi, menampilkan pemandangan kota

                Layout - ruang duduk terpisah
                Internet - WiFi Gratis
                Hiburan - Televisi LCD dengan channel TV premium channels
                Makan Minum - Pembuat kopi/teh, minibar, layanan kamar 24-jam, air minum kemasan gratis,
                termasuk sarapan
                Untuk tidur - Seprai kualitas premium dan gorden/tirai kedap cahaya
                Kamar Mandi - Kamar mandi pribadi dengan bathtub dan shower terpisah, jubah mandi, dan sandal
                Kemudahan - Brankas (muat laptop), Meja tulis, dan Telepon; tempat tidur lipat/tambahan tersedia
                berdasarkan permintaan
                Kenyamanan - AC dan layanan pembenahan kamar harian
                Merokok/Dilarang Merokok
                '
            ]
        )
        ->has(
            Room::factory()->count(5)->state(function (array $attributes, RoomType $type) {
                return ['bed_type' => fake()->randomElement(explode(',',$type->bed_options)) ];
            })
        )
        ->create();
    }
}

// select booking_id, r.created_at as tanggal_reservasi, c.name as customer_name, total as harga_reservasi, adults, kids, check_in, check_out
// from reservations r
// join customers c using(customer_id)
// where month(r) = 3

// SELECT *
// FROM transactions t
// join reservations r using (reservation_id)
// left join transactions b on b.reservation_id = r.reservation_id and b.type = 'bail'

// WHERE 1


// SELECT
// t.created_at as tanggal,
// t.no_invoice,
// fo.name as 'Front Office',
// r.booking_id,
// c.name,
// c.address,
// r.check_in,
// r.check_out,
// r.adults,
// r.kids,
// r.total / 30 as tax,
// r.total + (r.total / 30) as total,
// b.amount as jaminan,
// 300000 as deposit,
// (r.total + (r.total / 30)) - (b.amount + 300000) as cash
// FROM transactions t
// join reservations r using (reservation_id)
// join users fo using(user_id)
// join customers c using(customer_id)

// left join transactions b on b.reservation_id = r.reservation_id and b.type = 'bail'

// WHERE r.reservation_id = 11 and t.type='settled';

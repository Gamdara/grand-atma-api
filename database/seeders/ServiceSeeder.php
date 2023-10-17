<?php

namespace Database\Seeders;

use App\Models\Service;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::factory()->count(5)->sequence(
            ['name' => 'Extra Bed', 'unit' => 'bed', 'fare' => '100000'],
            ['name' => 'Laundry', 'unit' => 'flat', 'fare' => '30000'],
            ['name' => 'Meeting Room', 'unit' => 'jam', 'fare' => '250000'],
            ['name' => 'Breakfast', 'unit' => 'pax', 'fare' => '50000'],
            ['name' => 'Massage', 'unit' => 'orang', 'fare' => '75000']
        )->create();
    }
}

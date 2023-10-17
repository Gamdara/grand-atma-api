<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::factory()->count(10)->state(function (array $attributes) {
            return ['role_id' => Role::where('name', 'customer')->first()->role_id];
        })
        ->has(
            Customer::factory()->count(1)
        )
        ->create();
    }
}

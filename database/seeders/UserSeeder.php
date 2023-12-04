<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // dd(Role::where('name', 'owner'));
        User::factory()->count(5)->sequence(
            [
                'role_id' => Role::where('name', 'owner')->first()->role_id,
                'email' => 'owner@mail.com'
            ],
            [
                'role_id' => Role::where('name', 'general-manager')->first()->role_id,
                'email' => 'general@mail.com'
            ],
            [
                'role_id' => Role::where('name', 'front-office')->first()->role_id,
                'email' => 'front@mail.com'
            ],
            [
                'role_id' => Role::where('name', 'sales-marketing')->first()->role_id,
                'email' => 'sales@mail.com'
            ],
            [
                'role_id' => Role::where('name', 'admin')->first()->role_id,
                'email' => 'admin@mail.com'
            ]
        )->create();

        User::factory()->count(1)->state(function (array $attributes) {
            return ['role_id' => Role::where('name', 'customer')->first()->role_id, 'email' => 'customer@mail.com'];
        })
        ->has(
            Customer::factory()->count(1)
        )
        ->create();
    }
}

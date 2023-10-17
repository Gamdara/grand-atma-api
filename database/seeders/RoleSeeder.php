<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::factory()->count(6)->sequence(
            ['name' => 'owner'],
            ['name' => 'general-manager'],
            ['name' => 'front-office'],
            ['name' => 'sales-marketing'],
            ['name' => 'admin'],
            ['name' => 'customer']
        )->create();
    }
}

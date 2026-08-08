<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Role::create(['name' => 'admin']);

        Role::create(['name' => 'instructor_l1']);

        Role::create(['name' => 'instructor_l2']);

        Role::create(['name' => 'student']);
    }
}

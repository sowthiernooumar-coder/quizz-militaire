<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subject::create([
            'name' => 'Topographie'
        ]);

        Subject::create([
            'name' => 'Armement'
        ]);

        Subject::create([
            'name' => 'Tactique'
        ]);

    }
}

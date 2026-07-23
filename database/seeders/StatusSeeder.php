<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([
            ['name' => 'PENDING', 'step' => 0],
            ['name' => 'Accepted', 'step' => 1],
            ['name' => 'Processing...', 'step' => 2],
            ['name' => 'Ready', 'step' => 3],
            ['name' => 'Complete!', 'step' => 4],
        ]);
    }
}

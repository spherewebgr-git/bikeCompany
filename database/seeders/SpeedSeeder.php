<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('speeds')->insert([
            ['gears' => '3'],
            ['gears' => '6'],
            ['gears' => '7'],
            ['gears' => '10'],
            ['gears' => '18'],
            ['gears' => '21'],
            ['gears' => '24'],
        ]);
    }
}

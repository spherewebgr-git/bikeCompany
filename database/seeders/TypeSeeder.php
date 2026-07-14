<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('types')->insert([
            ['name' => 'Road'],
            ['name' => 'Mountain'],
            ['name' => 'Hybrid'],
            ['name' => 'Touring'],
            ['name' => 'Racing'],
            ['name' => 'BMX'],
            ['name' => 'Cruiser'],
            ['name' => 'City'],
            ['name' => 'Electric'],
            ['name' => 'Folding'],
            ['name' => 'Tandem'],
            ['name' => 'Gravel'],
            ['name' => 'Track'],
            ['name' => 'Fixed-gear'],
            ['name' => 'Cyclocross'],
            ['name' => 'Commuter'],
            ['name' => 'Trail'],
            ['name' => 'Fat-bike'],
            ['name' => 'Enduro'],
            ['name' => 'Downhill'],
        ]);
    }
}

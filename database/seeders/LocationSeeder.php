<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
            ['name' => 'Syntagma Square',
             'latitude' => '37.974557794664676', 'longitude' => '23.72597171839146'
            ],
            ['name' => 'Monastiraki',
                'latitude' => '37.97555086920729', 'longitude' => '23.7348928112215'
            ],
            ['name' => 'Psirri',
                'latitude' => '37.978351606145125', 'longitude' => '23.724807883033176'
            ],
        ]);
    }
}

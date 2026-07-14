<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\Type;
use App\Models\Speed;
use App\Models\Provision;
use App\Models\Price;

class BikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bikes = json_decode(file_get_contents(database_path('data/bikes.json')), true);

        foreach ($bikes as $bike) {
            DB::table('bikes')->insert([
                'colour' => $bike['colour'],
                'image_path' => 'https://vescocycles.com/products/vesco-downtown-26t-inch-white-cycle?srsltid=AfmBOopkvkL3-RFIWK4hYb78lp4DRjfAuBSE268kOfqTSKUjXRJbR3PS',

                'type_id' => Type::inRandomOrder()->value('id'),
                'brand_id' => Brand::inRandomOrder()->value('id'),
                'speed_id' => Speed::inRandomOrder()->value('id'),
                'provision_id' => Provision::inRandomOrder()->value('id'),

            ]);
        }
    }
}

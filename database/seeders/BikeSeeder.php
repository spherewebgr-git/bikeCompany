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

function generateSku(): string
{
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $parts = [];

    for ($i = 0; $i < rand(2, 4); $i++)
    {
        $part = '';

        for ($j = 0; $j < rand(3,4); $j++)
        {
            $part .= $chars[rand(0, strlen($chars) - 1)];
        }

        $parts[] = $part;
    }

    return implode('-', $parts);
}

function generateSerialNum(): string
{
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $part = '';

    for ($i = 0; $i < rand(8, 18); $i++)
    {
        $part .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $part;
}

class BikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bikes = json_decode(file_get_contents(database_path('data/bikes.json')), true);

        foreach ($bikes as $bike)
        {
            $provisiontype = Provision::inRandomOrder()->first();

            if ($provisiontype->name == 'buy')
            {
                DB::table('bikes')->insert([
                    'SKU' => generateSku(),
                    'colour' => $bike['colour'],
                    'type_id' => Type::inRandomOrder()->value('id'),
                    'brand_id' => Brand::inRandomOrder()->value('id'),
                    'speed_id' => Speed::inRandomOrder()->value('id'),
                    'provision_id' => $provisiontype->id,

                    'quantity' => rand(1, 50),
                    'serialnum' => NULL,
                ]);
            }

            else
            {
                DB::table('bikes')->insert([
                    'SKU' => generateSku(),
                    'colour' => $bike['colour'],
                    'type_id' => Type::inRandomOrder()->value('id'),
                    'brand_id' => Brand::inRandomOrder()->value('id'),
                    'speed_id' => Speed::inRandomOrder()->value('id'),
                    'provision_id' => $provisiontype->id,

                    'quantity' => NULL,
                    'serialnum' => generateSerialNum(),
                ]);
            }
        }
    }
}

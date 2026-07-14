<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Bike;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        $bikes = Bike::with('provision')->get();

        foreach ($bikes as $bike) {

            switch ($bike->provision->name) {

                case 'buy':

                    DB::table('prices')->insert([
                        'bike_id' => $bike->id,
                        'price' => rand(300, 5000),
                    ]);

                    break;


                case 'rent':

                    DB::table('prices')->insert([
                        [
                            'bike_id' => $bike->id,
                            'price' => rand(2, 9) . ' €/hour',
                        ],
                        [
                            'bike_id' => $bike->id,
                            'price' => rand(10, 39) . ' €/day',
                        ],
                        [
                            'bike_id' => $bike->id,
                            'price' => rand(40, 150) . ' €/week',
                        ],
                    ]);

                    break;
            }
        }
    }
}
